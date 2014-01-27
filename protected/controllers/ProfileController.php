<?php
class ProfileController extends Controller {
    public function filters() {
        return array(
            'login+update',
        );
    }    
    
    public $layout = 'profile';
    
    public function actionCreate() {
        $this->pageTitle = 'Dodawanie konta';
        
        $model = new User('create');
        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            if ($model->validate()) {
                try {
                    if (!$model->save(false))
                        throw new CDbException('Dodawanie użytkownika ' . CHtml::encode($model) . ' zakończone niepowodzeniem!');

                    Msg::add('success', 'Dodawanie uzytkownika ' . CHtml::encode($model) . ' zakończone sukcesem.',true);
                    $this->redirect($this->createUrl('login'));
                } catch (CException $e) {
                    throw new CHttpException(500, ($e instanceof CDbException) ? 'Wystąpil błąd podczas operacji bazodanowej. ' . $e->getMessage() : $e->getMessage());
                }
            }
        }           
        
        $this->render('create',array(
            'model'=>$model
        ));
    }
    
    public function actionUpdate() {
        $this->layout = 'main';
        $this->pageTitle = 'Zmiana danych profilu';
        
        $model = $this->loadModel(Yii::app()->user->id);
        $model->scenario = 'update';
        $model->password = '';
        
        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            if ($model->validate()) {
                try {
                    if (!$model->save(false))
                        throw new CDbException('Edytowanie danych użytkownika ' . CHtml::encode($model) . ' zakończone niepowodzeniem!');

                    Msg::add('success', 'Edytowanie danych użytkownika ' . CHtml::encode($model) . ' zakończone sukcesem.');

                } catch (CException $e) {
                    throw new CHttpException(500, ($e instanceof CDbException) ? 'Wystąpil błąd podczas operacji bazodanowej. ' . $e->getMessage() : $e->getMessage());
                }
            }
        }   
        
        $this->render('update',array(
            'model'=>$model
        ));        
    }
    
    public function actionLogin() {
        $this->pageTitle = 'Logowanie';
        
        $model = new User('login');
        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            if ($model->validate()) {
                $returnUrl = Yii::app()->user->returnUrl;
                $identity = new UserIdentity($model->email, sha1($model->password));
                if ($identity->authenticate()) {
                    $duration=$model->rememberMe ? 3600*24*30 : 0; // 30 dni
                    
                    Yii::app()->user->login($identity,$duration);
                    Yii::app()->request->redirect($returnUrl);
                } else {
                    switch ($identity->errorCode) {
                        case UserIdentity::ERROR_USERNAME_INVALID:
                            $model->addError('email','Wskazany adres email nie istnieje w systemie.');
                            break;

                        case UserIdentity::ERROR_PASSWORD_INVALID:
                            $model->addError('password','Nieprawidłowe hasło.');
                            break;

                        case UserIdentity::ERROR_ACCOUNT_INACTIVE:
                            $model->addError('email','Konto nie jest aktywne.');
                            break;
                    }
                }
            }
        }   
        
        $this->render('login',array(
            'model'=>$model
        ));
    }
    
    public function actionLogout() {
        $r = Yii::app()->user->returnUrl;
        Yii::app()->user->logout();
        Yii::app()->request->redirect($r);        
    }
        
    public function actionPasswordReset() {
        $this->pageTitle = 'Resetowanie hasła';
        
        $model = new User('passwordReset');        
        $email = Yii::app()->request->getQuery('email',null);
        if ($email!==null)
            $model->email = $email;
        
        $token = Yii::app()->request->getQuery('token',null);
        
        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            if ($model->validate()) {
                try {
                    $user = $model->findByAttributes(array('email'=>$model->email));
                    if ($user===null)
                        throw new CHttpException (404,'Konto "'.$model->email.'" nie istnieje.');
                    
                    $user->scenario = $model->scenario;
                    
                    if ($token===null) {
                        $resetToken = uniqid();
                        $user->resetToken = $resetToken;

                        if (!$user->save(false))
                            throw new CDbException('Zapisywanie tokena użytkownika "' . CHtml::encode($user) . '" zakończone niepowodzeniem!');
                   
                        $mailer = Yii::createComponent('ext.mailer.EMailer');
                        $mailer->IsHTML();
                        $mailer->IsMail();
                        $mailer->From = 'gilek@o2.pl';
                        $mailer->AddAddress($user->email);
                        $mailer->FromName = 'Web Notifier';
                        $mailer->CharSet = 'UTF-8';
                        $mailer->Subject =  'Prośba o nowe hasło';
                        $mailer->getView('passwordReset',array(
                            'email'=>$user->email,
                            'passwordResetURL'=>Yii::app()->request->getHostInfo().$this->createUrl('profile/passwordReset',array('email'=>$user->email,'token'=>$resetToken)),
                        ));                        
                        if ($mailer->send()) 
                            Msg::add('success', 'Instrukcje resetowania zostały wysłane na adres email ' . CHtml::encode($model) . '.');
                        else
                            Msg::add('error','Nie udało się wysłać instrukcji resetowania na adres email ' . CHtml::encode($model) . '.');
                    } else { //jest token
                        if (strlen($user->resetToken)==0 || $user->resetToken!=$token)
                            $model->addError ('email', 'Wartość "'.CHtml::encode($model->email).'" atrybutu Email jest nieprawidłowa.');
                        else {
                            $user->password = $model->password;
                            $user->resetToken=null;
                            if (!$user->save(false))
                                throw new CDbException('Zmiana hasła użytkownika ' . CHtml::encode($model) . ' zakończona niepowodzeniem!');

                            Msg::add('success','Zmiana hasła użytkownika ' . CHtml::encode($model) . ' zakończona sukcesem.',true);
                            $this->redirect(array('login'));
                        }    
                    }
                    

                } catch (CException $e) {
                    throw new CHttpException(500, ($e instanceof CDbException) ? 'Wystąpil błąd podczas operacji bazodanowej. ' . $e->getMessage() : $e->getMessage());
                }                
            }
        }          
        $this->render('passwordReset',array(
            'model'=>$model,
            'token'=>$token===null ? FALSE : TRUE,
        ));
    }
    
    public function loadModel($id,$with = array()) {
        $model = User::model()->with($with)->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'Użytkownik nie istnieje.');
        return $model;
    }       
    
}
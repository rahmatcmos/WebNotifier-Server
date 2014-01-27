<?php

class SiteController extends Controller {

    public function filters() {
        return array(
            array('application.filters.SessionFilter+list'),
            'login',
        );
    }

    public $defaultAction = 'list';

    public function actionSaveToStorage($id) {
        $sites = Yii::app()->user->getState('sites', null);
        if ($sites === null)
            $sites = array();

        $data = array();
        parse_str(Yii::app()->request->getPost('data'), $data);

        if (get_magic_quotes_gpc()) {
            function stripslashes_deep($value) {
                return is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
            }
            $data = array_map('stripslashes_deep', $data);
        }

        $data = current($data);

        if (array_key_exists($id, $sites))
            $data = array_merge((array) $sites[$id], $data);

        $sites[$id] = $data;
        Yii::app()->user->setState('sites', $sites);
        return true;
    }

    private function _getFromStorage($id) {
        $sites = Yii::app()->user->getState('sites', null);
        if ($sites === null || !array_key_exists($id, $sites))
            return null;

        $site = $sites[$id];
        unset($sites[$id]);
        Yii::app()->user->setState('sites', $sites);

        return $site;
    }

    public function actionInspector($id) {
        $this->layout = 'base';
        $this->pageTitle = 'Wybieranie elementu';

        $sites = Yii::app()->user->getState('sites', null);
        if ($sites === null || !array_key_exists($id, $sites))
            throw new CHttpException(404, 'Strona o wskazanym ID nie istnieje.');

        $model = new Site();
        $model->attributes = $sites[$id];
        $model->id = $id;
        $this->render('inspector', array(
            'model' => $model
        ));
    }

    public function actionLoader($url) {
        $this->pageTitle = '';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 20);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_USERAGENT, "Web Notifier 0.1a");        
        $content = curl_exec($curl);

        //na wypadek bledow
        libxml_use_internal_errors(true);

        $dom = new DOMDocument();
        $dom->loadHTML($content);

        $xpath = new DOMXpath($dom);
        $result = $xpath->query('/html[1]/head[1]');
        if ($result->length > 0) {
            $script = $dom->createElement('script');
            $script->setAttribute('src', Yii::app()->getBaseUrl(true) . '/js/inspector.js');
            $script->setAttribute('type', 'text/javascript');

            $style = $dom->createElement('style', '.asdqwe1234_outer { display:none; } .asdqwe1234_outer div { position:absolute; background:rgb(255,0,0); z-index:65000; }');
            $style->setAttribute('type', 'text/css');
            
            //todo
            // pomyslec nad zmiana url
            // nie wiem jak, ale dziala
            $base = $dom->createElement('base');
            $base->setAttribute('href', $url);
            $base->setAttribute('target', '_blank');
            
            $result->item(0)->appendChild($script);
            $result->item(0)->appendChild($style);
            $result->item(0)->appendChild($base);
        }
        echo $dom->saveHTML();
    }

    public function actionList() {
        $this->pageTitle = 'Lista stron';

        $model = new Site('search');
        $model->unsetAttributes();

        if (isset($_GET['Site']))
            $model->attributes = $_GET['Site'];

        $model->user = Yii::app()->user->id;
        $this->render('list', array(
            'model' => $model
        ));
    }

    public function actionCreate() {
        $this->pageTitle = 'Dodawanie strony';

        $model = new Site('create');

        if (isset($_POST['Site'])) {
            $model->attributes = $_POST['Site'];
            if ($model->validate()) {
                try {
                    if (!$model->save(false))
                        throw new CDbException('Dodawanie strony ' . CHtml::encode($model) . ' zakończone niepowodzeniem!');

                    Msg::add('success', 'Dodawanie strony ' . CHtml::encode($model) . ' zakończone sukcesem.', true);
                    $this->redirect($this->createUrl('list'));
                } catch (CException $e) {
                    throw new CHttpException(500, ($e instanceof CDbException) ? 'Wystąpil błąd podczas operacji bazodanowej. ' . $e->getMessage() : $e->getMessage());
                }
            }
        } else {
            $model->attributes = (array) $this->_getFromStorage(0);
        }

        $this->render('create', array(
            'model' => $model
        ));
    }

    public function actionUpdate($id) {
        $this->pageTitle = 'Edytowanie strony';

        $model = $this->loadModel($id);
        $model->scenario = 'update';

        if ($model->user != Yii::app()->user->id)
            throw new CHttpException(404, 'Strona nie istnieje.');

        if (isset($_POST['Site'])) {
            $model->attributes = $_POST['Site'];
            if ($model->validate()) {
                try {
                    if (!$model->save(false))
                        throw new CDbException('Edytowanie strony ' . CHtml::encode($model) . ' zakończone niepowodzeniem!');
                    Msg::add('success', 'Edytowanie strony ' . CHtml::encode($model) . ' zakończone sukcesem.');
                } catch (CException $e) {
                    throw new CHttpException(500, ($e instanceof CDbException) ? 'Wystąpil błąd podczas operacji bazodanowej. ' . $e->getMessage() : $e->getMessage());
                }
            }
        } else {
            $attributes = $this->_getFromStorage($id);
            if ($attributes !== null)
                $model->attributes = $attributes;
        }

        $this->render('update', array(
            'model' => $model
        ));
    }

    public function actionDelete($id) {
        $model = $this->loadModel($id);

        if ($model->user != Yii::app()->user->id)
            throw new CHttpException(404, 'Strona nie istnieje.');

        try {
            $model->delete();
            Msg::add('success', 'Usuwanie strony ' . CHtml::encode($model) . ' zakończone sukcesem.', true);
        } catch (CException $e) {
            Msg::add('error', 'Usuwanie strony ' . CHtml::encode($model) . ' zakończone niepowodzeniem. ' . $e->getMessage(), true);
        }

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_GET['returnUrl']) ? $_GET['returnUrl'] : $this->createUrl('list', $_GET));
    }

    public function loadModel($id, $with = array()) {
        $model = Site::model()->with($with)->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'Strona nie istnieje.');
        return $model;
    }

}
<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property string $email
 * @property string $password
 *
 * The followings are the available model relations:
 * @property Pages[] $pages
 */
class User extends CActiveRecord {

    public $oldAttributes;
    
    public $rememberMe;
    public $passwordRaw;    
    public $passwordChange;
    public $passwordConfirm;
    
    public function __toString() {
        return $this->email;
    }
    
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return User the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'users';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('email','email','allowEmpty'=>false),
            array('email', 'length', 'max' => 64),  
            array('email','unique','className'=>'User','attributeName'=>'email','skipOnError'=>true,'on'=>'create, update'),
            array('email','exist','className'=>'User','attributeName'=>'email','skipOnError'=>true,'on'=>'login, passwordReset'),
            array('passwordChange','compare', 'compareValue'=>1,'allowEmpty'=>true,'on'=>'update'),
            array('password','safe','on'=>'create, update, login, passwordReset'),
            array('passwordConfirm','compare','compareAttribute'=>'password','on'=>'create, passwordReset'),
            array('passwordConfirm','compare','compareAttribute'=>'password','when'=>'$model->passwordChange==1', 'on'=>'update'),
            
            array('rememberMe','compare','compareValue'=>1,'allowEmpty'=>true,'on'=>'login'),
            
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('email, password', 'safe', 'on' => 'search'),
        );
    }

    public function afterFind() {
        parent::afterFind();
        $this->oldAttributes = $this->getAttributes();
    }
    
    public function beforeSave() {
        parent::beforeSave();
        if ($this->scenario==='create' || $this->scenario==='passwordReset' || $this->scenario==='update' && $this->passwordChange == 1) {
            $this->passwordRaw = $this->password;
            $this->password = sha1($this->password);
        }
        
        if ( $this->scenario==='update' && $this->passwordChange == 0)
            $this->password = $this->oldAttributes['password'];
            
        return true;
    }
    
    public function afterSave() {
        $this->password = $this->passwordRaw;
    }
    
    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'sites' => array(self::HAS_MANY, 'Site', 'user'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'email' => 'Email',
            'password' => 'Hasło',
            'passwordConfirm' => 'Potwierdź hasło',
            'passwordChange' =>'zmiana',
            'rememberMe'=>'zapamiętaj mnie',  
            
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('email', $this->email, true);
        $criteria->compare('password', $this->password, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}
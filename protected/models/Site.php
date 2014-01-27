<?php

/**
 * This is the model class for table "sites".
 *
 * The followings are the available columns in table 'sites':
 * @property integer $id
 * @property integer $user
 * @property string $name
 * @property string $url
 * @property string $element
 * @property integer $checkAvailability
 * @property integer $checkStatus
 * @property integer $checkContent
 * @property string $contentTime
 * @property string $contentHash
 */
class Site extends CActiveRecord {
    public $oldAttributes;
    
    public function __toString() {
        return $this->name;
    }
    
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Site the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'sites';
    }

    public function hasMonitor() {
        return ($this->checkAvailability==1 || $this->checkStatus==1 || $this->checkContent==1) ? true : false;
    }
    
    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required'),
            array('name', 'length', 'max' => 128),
            array('url','required'),
            array('url','url','skipOnError'=>true),
            array('element', 'length', 'max'=> 256),
            array('checkAvailability, checkStatus, checkContent','in','range'=>array(0,1),'allowEmpty'=>true,),

            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('name, url, checkAvailability, checkStatus, checkContent, contentTime', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
           
        );
    }
    
    public function afterFind() {
        parent::afterFind();
        $this->oldAttributes = $this->attributes;
    }
    

    public function beforeSave() {
        parent::beforeSave();
        if ($this->isNewRecord)
            $this->user = Yii::app()->user->id;
        
        
        if (
           $this->checkContent==1 && 
          (
            $this->scenario==='create' || 
            ($this->scenario==='update' && ($this->oldAttributes['url'] !== $this->url || $this->oldAttributes['element'] !== $this->element || $this->contentHash===null))
          )
        ) {
            $info = Web::info($this->url,$this->element);
            if ($info['errno']===0 && $info['status']===200)
                $this->contentHash = Web::hash($info['content']);
        }
        
        if ($this->contentHash != $this->oldAttributes['contentHash'])
            $this->contentTime = new CDbExpression('now()');
        
        return true;    
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'name' => 'Nazwa',
            'url' => 'Adres',
            'checkAvailability' => 'Sprawdzaj dostępność',
            'checkStatus' => 'Sprawdzaj status',
            'checkContent' => 'Sprawdzaj zawartość',
            'contentTime'=>'Zmiana zawartości',
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

        $criteria->compare('user', $this->user);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('url', $this->url, true);
        $criteria->compare('checkAvailability', $this->checkAvailability);
        $criteria->compare('checkStatus', $this->checkStatus);
        $criteria->compare('checkContent', $this->checkContent);
        $criteria->compare('contentTime', $this->contentTime, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort'=>array(
                'sortVar'=>'sort'
            ),
            'pagination'=>array(
                'pageVar'=>'page',
            )
        ));
    }
    
    public function scopes() {
        return array(
            'sorted'=>array(
                'order'=>'t.name ASC, id ASC'
            )
        );
    }

}
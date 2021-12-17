<?php

/**
 * This is the model class for table "rooms".
 *
 * The followings are the available columns in table 'rooms':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $image
 * @property integer $valuePerHour
 *
 * The followings are the available model relations:
 * @property Reservations[] $reservations
 */
class Room extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rooms';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name,description,valuePerHour','required'),
			array('valuePerHour', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>55),
			array('name','checkRoomName'),
			array('image', 'required','on'=>'create'),
			array('image','file','types'=>'jpg, gif, png', 'allowEmpty'=>false,'on'=>'create'),
			array('description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, description, image, valuePerHour', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'reservations' => array(self::HAS_MANY, 'Reservations', 'roomId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'description' => 'Description',
			'image' => 'Image',
			'valuePerHour' => 'Value Per Hour',
		);
	}

	public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['image'];
		
		return $scenarios;
    }

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('valuePerHour',$this->valuePerHour);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Room the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function checkRoomName($attribute,$params){
		$searchExpression = self::model()->find('name=:roomName',array(':roomName'=>$this->name));
			
		if(isset($searchExpression) && $searchExpression->id !== $this->id)
			$this->addError($attribute,'Esse nome já existe!');
	}

	public function beforeUploadImage($fieldFile) {
		if(isset($fieldFile)){
			$this->image = $fieldFile->name;
		}
	}

	public function uploadImage($fieldFile) {
		if(!empty($fieldFile)){
			$random = rand(0,9999);
			$fileName = $random.'-'.$fieldFile;
			$this->image = $fileName;

			$fieldFile->saveAs('uploads/'.$fileName); 
		}
	}
}

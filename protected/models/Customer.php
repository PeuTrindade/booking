<?php

/**
 * This is the model class for table "customers".
 *
 * The followings are the available columns in table 'customers':
 * @property integer $id
 * @property string $name
 * @property integer $personCode
 * @property string $email
 * @property integer $phoneNumber
 * @property string $birthday
 *
 * The followings are the available model relations:
 * @property Reservations[] $reservations
 */
class Customer extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'customers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name,email,personCode,phoneNumber,birthday','required'),
			array('personCode, phoneNumber', 'numerical', 'integerOnly'=>true),
			array('name, email', 'length', 'max'=>255),
			array('email','checkCustomerEmail'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, personCode, email, phoneNumber, birthday', 'safe', 'on'=>'search'),
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
			'reservations' => array(self::HAS_MANY, 'Reservations', 'customerId'),
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
			'personCode' => 'Person Code',
			'email' => 'Email',
			'phoneNumber' => 'Phone Number',
			'birthday' => 'Birthday',
		);
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
		$criteria->compare('personCode',$this->personCode);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('phoneNumber',$this->phoneNumber);
		$criteria->compare('birthday',$this->birthday,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Customer the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function checkCustomerEmail($attribute,$param){
		$searchExpression = Customer::model()->find('email=:customerEmail',array(':customerEmail'=>$this->email));
			
		if(isset($searchExpression) && $searchExpression->id !== $this->id)
			$this->addError($attribute,'Esse email já foi cadastrado!');
	}

	public function formatDate($dateFormat) {
		$date = new DateTime($this->birthday);
		$this->birthday = $date->format($dateFormat);
	}
}

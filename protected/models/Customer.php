<?php

class Customer extends CActiveRecord {
	public function tableName() {
		return 'customers';
	}

	public function rules() {
		return array(
			array('name,email,personCode,phoneNumber,birthday','required'),
			array('personCode, phoneNumber', 'numerical', 'integerOnly'=>true),
			array('name, email', 'length', 'max'=>255),
			array('email','checkCustomerEmail'),
			array('id, name, personCode, email, phoneNumber, birthday', 'safe', 'on'=>'search'),
		);
	}

	public function relations() {
		return array(
			'reservations' => array(self::HAS_MANY, 'Reservations', 'customerId'),
		);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'personCode' => 'Person Code',
			'email' => 'Email',
			'phoneNumber' => 'Phone Number',
			'birthday' => 'Birthday',
		);
	}

	public function search() {
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

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function checkCustomerEmail($attribute,$param) {
		$searchExpression = Customer::model()->find('email=:customerEmail',array(':customerEmail'=>$this->email));
			
		if(isset($searchExpression) && $searchExpression->id !== $this->id)
			$this->addError($attribute,'Esse email já foi cadastrado!');
	}

	public function formatDate($dateFormat) {
		$date = new DateTime($this->birthday);
		$this->birthday = $date->format($dateFormat);
	}
}

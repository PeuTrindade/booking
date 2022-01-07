<?php

class Customer extends CActiveRecord {
	public function tableName() {
		return 'customers';
	}

	public function rules() {
		return array(
			array('name,email,personCode,phoneNumber,birthday','required','message'=>'{attribute} não pode estar em branco.'),
			array('personCode, phoneNumber', 'numerical', 'integerOnly'=>true,'message'=>'{attribute} deve ser um número inteiro.'),
			array('name, email', 'length', 'max'=>255),
			array('email','email','message'=>'{attribute} inválido.'),
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
			'name' => 'Nome',
			'personCode' => 'CPF/CNPJ',
			'email' => 'Email',
			'phoneNumber' => 'Telefone',
			'birthday' => 'Data de nascimento',
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

	public function beforeDelete() {
		$findCustomers = Reservation::model()->findAll('customerId=:customerId',array(':customerId'=>$this->id));
		
		foreach ($findCustomers as $key => $customer)
			$customer->delete();

		return parent::beforeDelete();
	}

	public function checkCustomerEmail($attribute,$param) {
		$searchExpression = Customer::model()->find('email=:customerEmail',array(':customerEmail'=>$this->email));
			
		if(isset($searchExpression) && $searchExpression->id !== $this->id)
			$this->addError($attribute,'Esse email já foi cadastrado!');
	}

	public function formatBirthdayDate($dateFormat) {
		$date = new DateTime($this->birthday);
		$this->birthday = $date->format($dateFormat);
	}
}

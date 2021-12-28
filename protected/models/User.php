<?php

class User extends CActiveRecord {
	public function tableName() {
		return 'users';
	}

	public function rules() {
		return array(
			array('username', 'length', 'max'=>55),
			array('email', 'length', 'max'=>255),
			array('password', 'length', 'max'=>32),
			array('id, username, email, password', 'safe', 'on'=>'search'),
		);
	}

	public function relations() {
		return array();
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'username' => 'Usuario',
			'email' => 'Email',
			'password' => 'Senha',
		);
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('password',$this->password,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function validatePassword($fieldPassword) {  
		$this->hashPassword();
        return CPasswordHelper::verifyPassword($fieldPassword,$this->password);
    }  
 
    public function hashPassword() {
		$this->password = CPasswordHelper::hashPassword($this->password);
    }
}

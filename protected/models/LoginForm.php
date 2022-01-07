<?php

class LoginForm extends CFormModel {
	public $username;
	public $password;
	private $_identity;

	public function rules() {
		return array(
			array('username, password', 'required','message'=>'{attribute} não pode estar em branco.'),
			array('password', 'authenticate'),
		);
	}

	public function attributeLabels() {
		return array(
			'id'=>'id',
			'username'=>'Usuário',
			'password'=>'Senha',
		);
	}

	public function authenticate($attribute,$params) {
		if(!$this->hasErrors()) {
			$this->_identity = new UserIdentity($this->username,$this->password);
			
			if(!$this->_identity->authenticate())
				$this->addError('password','Usuario ou senha incorreta');
		}
	}

	public function login() {
		if($this->_identity === null) {
			$this->_identity = new UserIdentity($this->username,$this->password);
			$this->_identity->authenticate();
		}
		else if($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
			Yii::app()->user->login($this->_identity);
			return true;
		}
		else
			return false;
	}
}

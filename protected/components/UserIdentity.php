<?php

class UserIdentity extends CUserIdentity {
	private $id;
	
	public function authenticate() {
		$findUserByUsername = User::model()->find('username=:username',array(':username'=>$this->username));
		
		if($this->verifyUserExists($findUserByUsername) && $this->verifyPasswordIsValid($findUserByUsername)) {
			$this->id = $findUserByUsername->id;	
		 	$this->username = $findUserByUsername->username;
		 	$this->errorCode = self::ERROR_NONE;

			return $this->errorCode == self::ERROR_NONE;
		}
	}

	private function verifyUserExists($user) {
		if(isset($user))
			return true;
		else 
			$this->errorCode = self::ERROR_USERNAME_INVALID;
	}

	private function verifyPasswordIsValid($user) {
		$isPasswordValid = $user->validatePassword($this->password);

		if($isPasswordValid)
			return true;
		else 
			$this->errorCode = self::ERROR_PASSWORD_INVALID;
	}

	public function getId() {
		return $this->id;
	}
}
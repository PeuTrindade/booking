<?php

class ConfirmationCodeController extends Controller {
	public function filters() {
		return array(
			'accessControl',
		);
	}

	public function accessRules() {
		return array(
			array('deny',
				'actions'=>array('index'),
				'users'=>array('?'),
			),
			array('allow', 
				'actions'=>array('index'),
				'users'=>array('@'),
			),
		);
	}

	public function actionIndex($ge,$ri) {
		$model = $this->loadCodeByGuestEmail($ge,$ri);
	
		if($model->validateCode())
			$this->render('index',array('isValid'=>true));
		else 
			$this->render('index',array('isValid'=>false));
	}

	private function loadCodeByGuestEmail($guestEmail,$reservationId) {
		$model = Confirmationcode::model()->findByAttributes(array('encryptedGuestEmail'=>$guestEmail,'reservationId'=>$reservationId));
		return $model;
	}
}
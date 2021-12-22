<?php

class ConfirmationCodeController extends Controller
{
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

	public function actionIndex($guestEmail,$reservationId) {
		$model = $this->loadCodeByGuestEmail($guestEmail,$reservationId);
		
		if($model->validateCode())
			$this->render('index',array('isValid'=>true));
		else 
			$this->render('index',array('isValid'=>false));
	}

	private function loadCodeByGuestEmail($guestEmail,$reservationId) {
		$model = Confirmationcode::model()->findByAttributes(array('guestEmail'=>$guestEmail,'reservationId'=>$reservationId));
		return $model;
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}
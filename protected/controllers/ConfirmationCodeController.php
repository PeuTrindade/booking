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

	public function actionIndex($guestEmail) {
		if(isset($guestEmail)){
			$searchConfirmationCode = Confirmationcode::model()->findAll('guestEmail=:guestEmail',array(':guestEmail'=>$guestEmail));
			
		}

		$this->render('index');
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
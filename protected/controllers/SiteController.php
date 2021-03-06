<?php

class SiteController extends Controller {
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

	public function actions() {
		return array(
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	public function actionIndex() {
		$this->render('index');
	}

	public function actionLogin() {
		$model = new LoginForm;

		if(isset($_POST['LoginForm'])) {
			$model->attributes = $_POST['LoginForm'];

			if($model->validate() && $model->login())
				$this->redirect($this->createUrl('site/index'));
		}

		$this->render('login',array('model'=>$model));
	}

	public function actionLogout() {
		Yii::app()->user->logout();
		$this->redirect($this->createUrl('site/login'));
	}
}
<?php

class CustomerController extends Controller
{
	public function filters() {
		return array(
			'accessControl',
		);
	}

	public function accessRules() {
		return array(
			array('deny',
				'actions'=>array('index','create','update','delete','view'),
				'users'=>array('?'),
			),
			array('allow', 
				'actions'=>array('index','create','update','delete','view'),
				'users'=>array('@'),
			),
		);
	}

	public function actionIndex() {
		$criteria = new CDbCriteria(array(
			'order'=>'id'
		));

		$dataProvider = new CActiveDataProvider('Customer', array(
			'pagination'=>array(
				'pageSize'=>5,
			),
			'criteria'=>$criteria,
		));

		$this->render('index',array('dataProvider'=>$dataProvider));
	}

	public function actionCreate() {
		$model = new Customer;
			
		if(isset($_POST['Customer'])) {
			$model->attributes = $_POST['Customer'];
			$fieldEmail = $_POST['Customer']['email'];
			
			if($model->checkCustomerEmail($fieldEmail) && $model->validate()) {
				$model->save();
				$this->redirect('index.php?r=customer/index');
			}
    	}

		$this->render('create',array('model'=>$model));
	}

	public function actionView($id) {
		$model = $this->loadCustomer();

		if(isset($model) && isset($id)){
			$model->formatDate('d/m/Y');
			$this->render('view',array('model'=>$model));
		} else {
			throw new CHttpException(404,'Essa página requisitada não existe!');
		}
	}

	public function actionDelete($id) {
		$model = $this->loadCustomer();

		if(isset($model) && isset($id)){
			$model->delete();
			$this->redirect('index.php?r=customer/index');
		} else {
			throw new CHttpException(404,'Essa página requisitada não existe!');
		}
	}

	public function actionUpdate($id) {
		$model = $this->loadCustomer();

		if(isset($model) && isset($id)){
			if(isset($_POST['Customer'])){
				$model->attributes = $_POST['Customer'];
				$fieldEmail = $_POST['Customer']['email'];

				if($model->checkCustomerEmail($fieldEmail,$id) && $model->validate()) {
					$model->save();
					$this->redirect('index.php?r=customer/view&id='.$id);
				}
			}
			$this->render('update',array('model'=>$model));	
		} else {
			throw new CHttpException(404,'Essa página requisitada não existe!');
		}
	}

	public function loadCustomer() {
		$customerId = $_GET['id'];

		if(isset($customerId)){
			$model = Customer::model()->findByPk($customerId);
			return $model;
		} else {
			throw new CHttpException(404,'Essa página requisitada não existe!');
		}
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
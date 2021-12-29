<?php

class CustomerController extends Controller {
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
			
			if($model->validate()) {
				$model->save();
				$this->redirect($this->createUrl('customer/index'));
			}
    	}

		$this->render('create',array('model'=>$model));
	}

	public function actionView($id) {
		$model = $this->loadCustomer($id);

		$model->formatBirthdayDate('d/m/Y');
		$this->render('view',array('model'=>$model));
	}

	public function actionDelete($id) {
		$model = $this->loadCustomer($id);

		$model->delete();
		$this->redirect($this->createUrl('customer/index'));
	}

	public function actionUpdate($id) {
		$model = $this->loadCustomer($id);

		if(isset($_POST['Customer'])){
			$model->attributes = $_POST['Customer'];

			if($model->validate()) {
				$model->save();
				$this->redirect($this->createUrl('customer/view',array('id'=>$id)));
			}
		}

		$this->render('update',array('model'=>$model));	
	}

	private function loadCustomer($customerId) {
		$model = Customer::model()->findByPk($customerId);

		if(isset($model) && isset($customerId))
			return $model;
		else 
			throw new CHttpException(404,'Essa página requisitada não existe!');
	}
}
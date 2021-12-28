<?php

class RoomController extends Controller {
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

		$dataProvider = new CActiveDataProvider('Room', array(
			'pagination'=>array(
				'pageSize'=>3,
			),
			'criteria'=>$criteria,
		));
			
		$this->render('index',array('dataProvider'=>$dataProvider));
	}

	public function actionCreate() {
		$model = new Room;
		$model->scenario = 'create';
			
		if(isset($_POST['Room'])) {
			$model->attributes = $_POST['Room'];
			$fieldFile = CUploadedFile::getInstance($model, 'image');
			$model->beforeUploadImage($fieldFile);

			if($model->validate()) {
				$model->uploadImage($fieldFile);
				$model->save();
				$this->redirect($this->createUrl('room/index'));
			}
    	}
		
		$this->render('create',array('model'=>$model));
	}

	public function actionUpdate($id){
		$model = $this->loadRoom($id);

		if(isset($model) && isset($id)) {
			if(isset($_POST['Room'])) {
				$model->attributes = $_POST['Room'];
				$fieldFile = CUploadedFile::getInstance($model, 'image');
				$model->beforeUploadImage($fieldFile);

				if($model->validate()) {
					$model->uploadImage($fieldFile);
					$model->save();
					$this->redirect($this->createUrl('room/view',array('id'=>$id)));
				}
			}
			$this->render('update',array('model'=>$model));	
		} else {
			throw new CHttpException(404,'Essa página requisitada não existe!');
		}
	}

	public function actionDelete($id) {
		$model = $this->loadRoom($id);

		if(isset($model) && isset($id)) {
			$model->delete();
			$this->redirect($this->createUrl('room/index'));
		} else {
			throw new CHttpException(404,'Essa página requisitada não existe!');
		}
	}

	public function actionView($id) {
		$model = $this->loadRoom($id);

		if(isset($model) && isset($id)) {
			$this->render('view',array('model'=>$model));
		} else {
			throw new CHttpException(404,'Essa página requisitada não existe!');
		}
	}
	
	private function loadRoom($roomId) {
		$model = Room::model()->findByPk($roomId);
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
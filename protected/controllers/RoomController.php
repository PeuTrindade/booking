<?php

class RoomController extends Controller
{
	public function actionIndex() {
		$user = Yii::app()->user->id;
		$criteria = new CDbCriteria(array(
			'order'=>'id'
		));

		$dataProvider = new CActiveDataProvider('Room', array(
			'pagination'=>array(
				'pageSize'=>3,
			),
			'criteria'=>$criteria,
		));

		if(isset($user)){
			$this->render('index',array('dataProvider'=>$dataProvider));
		} else {
			$this->redirect('index.php?r=site/login');
		}
	}

	public function actionCreate() {
		$user = Yii::app()->user->id;
		$model = new Room;
			
		if(isset($_POST['Room'])) {
			$model->attributes = $_POST['Room'];
			$fieldName = $_POST['Room']['name'];
			
			if($model->checkRoomName($fieldName) && $model->validate()) {
				$model->save();
				$this->redirect('index.php?r=room/index');
			}
    	}

		if(isset($user)){
			$this->render('create',array('model'=>$model));
		} else {
			$this->redirect('index.php?r=site/login');
		}
	}

	public function actionUpdate($id){
		$user = Yii::app()->user->id;
		$model = $this->loadRoom();

		if(isset($user)){
			if(isset($model) && isset($id)){
				if(isset($_POST['Room'])){
					$model->attributes = $_POST['Room'];
					$fieldName = $_POST['Room']['name'];

					if($model->checkRoomName($fieldName,$id) && $model->validate()) {
						$model->save();
						$this->redirect('index.php?r=room/index');
					}
				}
				$this->render('update',array('model'=>$model));	
			} else {
				throw new CHttpException(404,'Essa página requisitada não existe!');
			}
		} else {
			$this->redirect('index.php?r=site/login');
		}	
	}

	public function actionDeleteRoom($id) {
		$user = Yii::app()->user->id;
		$model = $this->loadRoom();

		if(isset($user)){
			if(isset($model) && isset($id)){
				$model->delete();
				$this->redirect('index.php?r=room/index');
			} else {
				throw new CHttpException(404,'Essa página requisitada não existe!');
			}
		} else {
			$this->redirect('index.php?r=site/login');
		}
	}

	public function actionView($id) {
		$user = Yii::app()->user->id;
		$model = $this->loadRoom();

		if(isset($user)){
			if(isset($model) && isset($id)){
				$this->render('view',array('model'=>$model));
			} else {
				throw new CHttpException(404,'Essa página requisitada não existe!');
			}
		} else {
			$this->redirect('index.php?r=site/login');
		}
	}

	public function loadRoom() {
		$roomId = $_GET['id'];

		if(isset($roomId)){
			$model = Room::model()->findByPk($roomId);
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
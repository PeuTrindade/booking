<?php

class ReservationController extends Controller
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
				'actions'=>array('index','create','update','delete','view','teste'),
				'users'=>array('@'),
			),
		);
	}
	
	public function actionIndex() {
		$criteria = new CDbCriteria(array(
			'order'=>'id'
		));

		$dataProvider = new CActiveDataProvider('Reservation', array(
			'pagination'=>array(
				'pageSize'=>5,
			),
			'criteria'=>$criteria,
		));

		$this->render('index',array('dataProvider'=>$dataProvider));
	}

	public function actionCreate() {
		$model = new Reservation;
		$customersNames = $this->returnArrayOfNames('Customer');
		$roomsNames = $this->returnArrayOfNames('Room');

		if(isset($_POST['Reservation'])){
			$model->attributes = $_POST['Reservation'];
		
			if($model->addCustomerAndRoomId() && $model->validate()){
				//$model->save();
				$model->sendEmailToGuests();
				//$this->redirect($this->createUrl('reservation/index'));
			}
		}
		$this->render('create',array('model'=>$model,'customersNames'=>$customersNames,'roomsNames'=>$roomsNames));
	}

	public function actionView($id) {
		$model = $this->loadReservation($id);

		if(isset($model) && isset($id)){
			$model->formatDate('d/m/Y');
			$this->render('view',array('model'=>$model));
		} else {
			throw new CHttpException(404,'Essa página requisitada não existe!');
		}
	}

	public function actionUpdate($id) {
		$model = $this->loadReservation($id);
		$customersNames = $this->returnArrayOfNames('Customer');
		$roomsNames = $this->returnArrayOfNames('Room');

		if(isset($model) && isset($id)){
			if(isset($_POST['Reservation'])){
				$model->attributes = $_POST['Reservation'];

				if($model->addCustomerAndRoomId() && $model->validate()) {
					$model->save();
					$this->redirect($this->createUrl('reservation/view',array('id'=>$id)));
				}
			}
			$this->render('update',array('model'=>$model,'customersNames'=>$customersNames,'roomsNames'=>$roomsNames));	
		} else {
			throw new CHttpException(404,'Essa página requisitada não existe!');
		}
	}

	public function actionDelete($id) {
		$model = $this->loadReservation($id);

		if(isset($model) && isset($id)){
			$this->deleteConfirmationCodes($id);
			$model->delete();
			$this->redirect($this->createUrl('reservation/index'));
		} else {
			throw new CHttpException(404,'Essa página requisitada não existe!');
		}
	}

	private function deleteConfirmationCodes($reservationId) {
		$findConfirmationCodes = Confirmationcode::model()->findAll('reservationId=:reservationId',array(':reservationId'=>$reservationId));
		foreach ($findConfirmationCodes as $key => $confirmationCode)
			$confirmationCode->delete();
	}

	private function loadReservation($reservationId) {
		$model = Reservation::model()->findByPk($reservationId);
		return $model;
	}

	private function returnArrayOfNames($model) {
		$namesArray = array();
		$items = $model::model()->findAll();

		foreach ($items as $key => $value) {
			$itemName = $value['name'];
			array_push($namesArray,array($itemName=>$itemName));
		}

		return $namesArray;
	}

	public function actionAjaxCalc() {
		$roomName = $_POST['ajaxRoomName'];
		$startTime = new DateTime($_POST['ajaxStartTime']);
		$endTime = new DateTime($_POST['ajaxEndTime']);

		$findRoomByName = Room::model()->findByAttributes(array('name'=>$roomName));
		$valuePerHour = $findRoomByName->valuePerHour;
		echo $this->calcTotalAmount($startTime,$endTime,$valuePerHour);
	}

	private function calcTotalAmount($startTime,$endTime,$valuePerHour) {
		$subtractTime = $endTime->diff($startTime,true);
		$arrayTime = explode(':',$subtractTime->format('%H:%i%'));
		$transformInMinutes = $arrayTime[0] * 60 + $arrayTime[1];

		$calc = $transformInMinutes * ($valuePerHour / 60);
		if($transformInMinutes >= 30)
			return 'R$'.round($calc,2);
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
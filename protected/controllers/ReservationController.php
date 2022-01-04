<?php

class ReservationController extends Controller {
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
				'actions'=>array('index','create','update','delete','view','ajaxcalc'),
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

		if(isset($_POST['Reservation'])) {
			$model->attributes = $_POST['Reservation'];
			
			if($model->validate()) {	
				$model->save();
				$model->sendEmailToGuests();
				$this->redirect($this->createUrl('reservation/index'));
			}
		}

		$this->render('create',array('model'=>$model,'customersNames'=>$customersNames,'roomsNames'=>$roomsNames));
	}

	public function actionView($id) {
		$model = $this->loadReservation($id);
		$model->formatBookingDate('d/m/Y');
		$customerName = $model->returnCustomerName($model->customerId);
		$roomName = $model->returnRoomName($model->roomId);

		$this->render('view',array('model'=>$model,'customerName'=>$customerName,'roomName'=>$roomName));
	}

	public function actionUpdate($id) {
		$model = $this->loadReservation($id);
		$customersNames = $this->returnArrayOfNames('Customer');
		$roomsNames = $this->returnArrayOfNames('Room');

		if(isset($_POST['Reservation'])){
			$model->attributes = $_POST['Reservation'];

			if($model->validate()) {
				$model->save();
				$this->redirect($this->createUrl('reservation/view',array('id'=>$id)));
			}
		}

		$this->render('update',array('model'=>$model,'customersNames'=>$customersNames,'roomsNames'=>$roomsNames));	
	}

	public function actionDelete($id) {
		$model = $this->loadReservation($id);

		$model->delete();
		$this->redirect($this->createUrl('reservation/index'));
	}

	private function loadReservation($reservationId) {
		$model = Reservation::model()->findByPk($reservationId);

		if(isset($model) && isset($reservationId))
			return $model;
		else 
			throw new CHttpException(404,'Essa página requisitada não existe!');
	}

	private function returnArrayOfNames($model) {
		$namesArray = array();
		$items = $model::model()->findAll();

		foreach ($items as $key => $value) {
			$itemId = $value['id'];
			$itemName = $value['name'];

			array_push($namesArray,array($itemId=>$itemName));
		}

		return $namesArray;
	}

	public function actionAjaxCalc() {
		$roomId = $_POST['ajaxRoomId'];
		$startTime = new DateTime($_POST['ajaxStartTime']);
		$endTime = new DateTime($_POST['ajaxEndTime']);

		$findRoomById = Room::model()->findByPk($roomId);
		$valuePerHour = $findRoomById->valuePerHour;

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
}
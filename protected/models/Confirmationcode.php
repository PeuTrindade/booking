<?php

include('libs/phpqrcode/qrlib.php');

class Confirmationcode extends CActiveRecord {
	public function tableName() {
		return 'confirmationcodes';
	}

	public function rules() {
		return array(
			array('reservationId', 'required'),
			array('reservationId', 'numerical', 'integerOnly'=>true),
			array('guestEmail', 'length', 'max'=>255),
			array('situation', 'length', 'max'=>55),
			array('id, guestEmail, reservationId, situation', 'safe', 'on'=>'search'),
		);
	}

	public function relations() {
		return array(
			'reservation' => array(self::BELONGS_TO, 'Reservations', 'reservationId'),
		);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'guestEmail' => 'Guest Email',
			'reservationId' => 'Reservation',
			'situation' => 'Situation',
		);
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('guestEmail',$this->guestEmail,true);
		$criteria->compare('reservationId',$this->reservationId);
		$criteria->compare('situation',$this->situation,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function uploadCode($params,$cleanGuestEmail) {
		$folder = 'qrcodes/';
		$fileName = $cleanGuestEmail.'.png';
		$path = $folder.$fileName;
		$codeContent = $this->returnCustomUrl($params);
		
		QRcode::png($codeContent,$path);
	}

	public function validateCode() {
		if($this->validateCodeSituation() && $this->validateCodeByDateAndTime()) {
			$this->invalidateCode();
			return true;
		}
		else 
			return false;
	}

	private function validateCodeSituation() {
		if($this->situation === 'valid')
			return true;
		else 
			return false;
	}

	private function validateCodeByDateAndTime() {
		$findReservationById = Reservation::model()->findByPk($this->reservationId);
		$validateCodeByDate = $this->validateCodeByDate($findReservationById);
		$validateStartTime = $this->validateStartTime($findReservationById);
		$validateTimeWithTolerance = $this->validateTimeWithTolerance($findReservationById);

		if($validateCodeByDate && $validateStartTime && $validateTimeWithTolerance)
			return true;
		else 
			return false;
	}

	private function validateCodeByDate($reservation) {
		$currentDate = $this->returnCurrentDate('Y-m-d');
		
		if($reservation->bookingDate === $currentDate)
			return true;
		else 
			return false;
	}

	private function validateStartTime($reservation) {
		$currentTime = $this->returnCurrentTime('H:i:00');
		
		if($currentTime >= $reservation->startTime)
			return true;
		else 
			return false;
	}

	private function validateTimeWithTolerance($reservation) {
		$currentTime = $this->returnCurrentTime('H:i:00');
		$timeWithTolerance = new DateTime($reservation->startTime, new DateTimeZone('America/Sao_Paulo'));
		$timeWithTolerance->add(new DateInterval('PT900S'));
		$formatedTimeWithTolerance = $timeWithTolerance->format('H:i:00');

		if($currentTime <= $formatedTimeWithTolerance)
			return true;
		else 
			return false;
	}

	private function invalidateCode() {
		$this->situation = 'invalid';
		$this->save();
	}

	private function returnCurrentDate($format) {
		$currentDate = new DateTime('now');
		return $currentDate->format($format);
	}

	private function returnCurrentTime($format) {
		$currentTime = new DateTime('now',new DateTimeZone('America/Sao_Paulo'));
		return $currentTime->format($format);
	}

	private function returnCustomUrl($params) {
		$hostInfo = Yii::app()->request->hostInfo;
		$scriptUrl = Yii::app()->request->scriptUrl;
		$absoultePath = '?r=confirmationCode/index';
		$customPath = '&ge='.$params['guestEmail'].'&ri='.$params['reservationId'];
		
		return $hostInfo.$scriptUrl.$absoultePath.$customPath;
	}
}

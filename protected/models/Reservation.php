<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

class Reservation extends CActiveRecord {
	private $errors = array();

	public function tableName() {
		return 'reservations';
	}

	public function rules() {
		return array(
			array('customerName,roomName,bookingDate,startTime,endTime,totalAmount', 'required'),
			array('bookingDate, startTime, endTime, guestsEmails', 'safe'),
			array('startTime','checkStartTimeIsAllow'),
			array('endTime','checkEndTimeIsAllow'),
			array('id, customerId, roomId, bookingDate, startTime, endTime, totalAmount, guestsEmails', 'safe', 'on'=>'search'),
		);
	}

	public function relations() {
		return array(
			'qrcodes' => array(self::HAS_MANY, 'Qrcodes', 'reservationId'),
			'customer' => array(self::BELONGS_TO, 'Customers', 'customerId'),
			'room' => array(self::BELONGS_TO, 'Rooms', 'roomId'),
		);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'customerId' => 'Customer',
			'roomId' => 'Room',
			'bookingDate' => 'Booking Date',
			'startTime' => 'Start Time',
			'endTime' => 'End Time',
			'totalAmount' => 'Total Amount',
			'guestsEmails' => 'Guests Emails',
		);
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('customerId',$this->customerId);
		$criteria->compare('roomId',$this->roomId);
		$criteria->compare('bookingDate',$this->bookingDate,true);
		$criteria->compare('startTime',$this->startTime,true);
		$criteria->compare('endTime',$this->endTime,true);
		$criteria->compare('totalAmount',$this->totalAmount);
		$criteria->compare('guestsEmails',$this->guestsEmails,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function beforeDelete() {
		$findConfirmationCodes = Confirmationcode::model()->findAll('reservationId=:reservationId',array(':reservationId'=>$this->id));
		
		foreach ($findConfirmationCodes as $key => $confirmationCode)
			$confirmationCode->delete();

		return parent::beforeDelete();
	}

	public function beforeValidate() {
		$findCustomerByName = Customer::model()->find('name=:customerName',array(':customerName'=>$this->customerName));
		$findRoomByName = Room::model()->find('name=:roomName',array(':roomName'=>$this->roomName));

		$this->customerId = $findCustomerByName->id;
		$this->roomId = $findRoomByName->id;

		return parent::beforeValidate();
	} 

	public function checkStartTimeIsAllow($attribute,$params) {
		$searchForDateAndRoom = self::model()->findAllByAttributes(array('bookingDate'=>$this->bookingDate,'roomId'=>$this->roomId));
		
		foreach ($searchForDateAndRoom as $key => $reservation) {
			$reservationStartTime = $this->returnFormatedTime($reservation['startTime'],'H:i');
			$reservationEndTime = $this->returnFormatedTime($reservation['endTime'],'H:i');

			if($reservation['id'] !== $this->id)
				if($this->startTime >= $reservationStartTime && $this->startTime < $reservationEndTime)
					$this->addError($attribute,'Horario ocupado!');
		}
	}

	public function checkEndTimeIsAllow($attribute,$params) {
		$searchForDateAndRoom = self::model()->findAllByAttributes(array('bookingDate'=>$this->bookingDate,'roomId'=>$this->roomId));
		$startTime = new DateTime($this->startTime);
		$endTime = new DateTime($this->endTime);
		$subtractTime = $endTime->diff($startTime,true);
		$formatedSubtractedTime = $subtractTime->format('%H:%i%');

		foreach ($searchForDateAndRoom as $key => $reservation) {
			$reservationStartTime = $this->returnFormatedTime($reservation['startTime'],'H:i');
			$reservationEndTime = $this->returnFormatedTime($reservation['endTime'],'H:i');

			if($reservation['id'] !== $this->id)
				if($this->endTime > $reservationStartTime && $this->endTime <= $reservationEndTime)
					$this->addError($attribute,'Horario ocupado!');
		}

		$this->checkTimeValues($attribute,$formatedSubtractedTime);
	}

	public function checkTimeValues($attribute,$formatedSubtractedTime) {
		if($formatedSubtractedTime < '00:30')
			$this->addError($attribute,'Tempo minimo de reserva deve ser de 30 minutos');
		else if($this->endTime < $this->startTime)
			$this->addError($attribute,'Horario de termino deve ser maior que o de inicio');
	}

	public function returnFormatedTime($value,$timeFormat) {
		$time = new DateTime($value);
		return $time->format($timeFormat);
	}

	public function formatBookingDate($dateFormat) {
		$date = new DateTime($this->bookingDate);
		$this->bookingDate = $date->format($dateFormat);
	}

	public function sendEmailToGuests() {
		if(!empty($this->guestsEmails)){
			$mail = new PHPMailer(true);
			$guestsEmails = explode(',',$this->guestsEmails);
			$this->formatBookingDate('d/m/Y');

			$this->emailConfigurations($mail);
			$this->fireEmail($mail,$guestsEmails);
		}
	}

	private function emailConfigurations($mail) {                    
    	$mail->isSMTP();                                         
    	$mail->Host = 'smtp.gmail.com';  
		$mail->SMTPSecure = 'tls';                 
    	$mail->SMTPAuth = true;                                  
    	$mail->Username = 'devpedrotrindade@gmail.com';                   
    	$mail->Password = 'pedro2804';                           
    	$mail->Port = '587';     
	}

	private function fireEmail($mail,$guestsEmails) {
		$mail->setFrom($mail->Username,'Booking - Agendamento de salas');
		$mail->Subject = 'Agendamento para o dia '.$this->bookingDate;
		$mail->Body = 'Olá, estamos felizes em saber que você irá visitar nosso espaço no dia '.$this->bookingDate.'. Abaixo segue o seu QRcode, ele será usado como verificação, portanto, não perca esse email!';
		$mail->isHTML(true);

		foreach ($guestsEmails as $key => $guestEmail) {
			$cleanGuestEmail = $this->clearGuestEmail($guestEmail);
			$encryptedGuestEmail = md5($guestEmail);
			$qrcode = new Confirmationcode;
			$qrcode->uploadCode(array('guestEmail'=>$encryptedGuestEmail,'reservationId'=>$this->id),$cleanGuestEmail);
			$mail->addAddress($guestEmail);
			$mail->addAttachment('qrcodes/'.$cleanGuestEmail.'.png');

			$mail->send();	
			$this->saveQrcode($qrcode,$guestEmail,$encryptedGuestEmail);
			$mail->clearAttachments();
			$mail->ClearAddresses();
		}	
	}

	private function saveQrcode($qrcode,$guestEmail,$encryptedGuestEmail) {
		$qrcode->guestEmail = $guestEmail;
		$qrcode->encryptedGuestEmail = $encryptedGuestEmail;
		$qrcode->reservationId = $this->id;
		$qrcode->situation = 'valid';

		$qrcode->save();
	}

	private function clearGuestEmail($guestEmail) {
		$cleanGuestEmail = explode('@',$guestEmail)[0];
		return $cleanGuestEmail;
	}
}

<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

/**
 * This is the model class for table "reservations".
 *
 * The followings are the available columns in table 'reservations':
 * @property integer $id
 * @property integer $customerId
 * @property integer $roomId
 * @property string $bookingDate
 * @property string $startTime
 * @property string $endTime
 * @property double $totalAmount
 * @property string $guestsEmails
 *
 * The followings are the available model relations:
 * @property Qrcodes[] $qrcodes
 * @property Customers $customer
 * @property Rooms $room
 */
class Reservation extends CActiveRecord
{
	private $errors = array();

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'reservations';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customerName,roomName,bookingDate,startTime,endTime,totalAmount', 'required'),
			array('totalAmount', 'numerical'),
			array('bookingDate, startTime, endTime, guestsEmails', 'safe'),
			array('startTime','checkStartTimeIsAllow'),
			array('endTime','checkEndTimeIsAllow'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customerId, roomId, bookingDate, startTime, endTime, totalAmount, guestsEmails', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'qrcodes' => array(self::HAS_MANY, 'Qrcodes', 'reservationId'),
			'customer' => array(self::BELONGS_TO, 'Customers', 'customerId'),
			'room' => array(self::BELONGS_TO, 'Rooms', 'roomId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
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

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

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

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Reservation the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function addCustomerAndRoomId() {
		$customer = Customer::model()->find('name=:customerName',array(':customerName'=>$this->customerName));
		$room = Room::model()->find('name=:roomName',array(':roomName'=>$this->roomName));

		if(!isset($customer)) {
			$this->addError('customerName','Cliente não encontrado!');
		}
		else if(!isset($room)) {
			$this->addError('roomName','Sala não encontrada!');
		}
		else {
			$this->customerId = $customer->id;
			$this->roomId = $room->id;
			return true;
		}	
	} 

	public function checkStartTimeIsAllow($attribute,$params) {
		$reservationAtThisDate = self::model()->findAll('bookingDate=:bookingDate',array(':bookingDate'=>$this->bookingDate));
		$formatStartTime = $this->returnFormatedTime($this->startTime,'His');
		
		foreach ($reservationAtThisDate as $key => $reservation) {
			$reservationStartTime = $this->returnFormatedTime($reservation['startTime'],'His');
			$reservationEndTime = $this->returnFormatedTime($reservation['endTime'],'His');

			if($reservation['roomId'] === $this->roomId && $reservation['id'] !== $this->id) {
				if($formatStartTime >= $reservationStartTime && $formatStartTime < $reservationEndTime)
					$this->addError($attribute,'Horario ocupado!');
			}
		}
	}

	public function checkEndTimeIsAllow($attribute,$params) {
		$formatStartTime = $this->returnFormatedTime($this->startTime,'His');
		$formatEndTime = $this->returnFormatedTime($this->endTime,'His');
		$countMinutes = $formatEndTime - $formatStartTime;

		if($formatEndTime <= $formatStartTime)
			$this->addError($attribute,'Horario de termino deve ser maior que o de inicio');
		else if($countMinutes < 3000)
			$this->addError($attribute,'Tempo minimo de reserva deve ser de 30 minutos');
	}

	public function returnFormatedTime($value,$timeFormat) {
		$time = new DateTime($value);
		return $time->format($timeFormat);
	}

	public function formatDate($dateFormat) {
		$date = new DateTime($this->bookingDate);
		$this->bookingDate = $date->format($dateFormat);
	}

	public function sendEmailToGuests() {
		if(!empty($this->guestsEmails)){
			$mail = new PHPMailer(true);
			$guestsEmails = explode(',',$this->guestsEmails);
			$this->formatDate('d/m/Y');

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
    	$mail->Password = '********';                           
    	$mail->Port = '587';     
	}

	private function fireEmail($mail,$guestsEmails) {
		$mail->setFrom($mail->Username,'Booking - Agendamento de salas');
		$mail->Subject = 'Agendamento para o dia '.$this->bookingDate;
		$mail->Body = 'Olá, estamos felizes em saber que você irá visitar nosso espaço no dia '.$this->bookingDate.'. Abaixo segue o seu QRcode, ele será usado como verificação, portanto, não perca esse email!';
		$mail->isHTML(true);

		foreach ($guestsEmails as $key => $guestEmail) {
			$cleanGuestEmail = $this->clearGuestEmail($guestEmail);
			$qrcode = new Confirmationcode;
			$qrcode->uploadCode(array('guestEmail'=>$guestEmail,'reservationId'=>$this->id),$cleanGuestEmail);
			$mail->addAddress($guestEmail);
			$mail->addAttachment('qrcodes/'.$cleanGuestEmail.'.png');

			$mail->send();	
			$this->saveQrcode($qrcode,$guestEmail);
			$mail->clearAttachments();
			$mail->ClearAddresses();
		}	
	}

	private function saveQrcode($qrcode,$guestEmail) {
		$qrcode->guestEmail = $guestEmail;
		$qrcode->reservationId = $this->id;
		$qrcode->situation = 'valid';

		$qrcode->save();
	}

	private function clearGuestEmail($guestEmail) {
		$cleanGuestEmail = explode('@',$guestEmail)[0];
		return $cleanGuestEmail;
	}
}

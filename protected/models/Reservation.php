<?php

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
		$customerName = $this->customerName;
		$roomName = $this->roomName;

		$customer = Customer::model()->find('name=:customerName',array(':customerName'=>$customerName));
		$room = Room::model()->find('name=:roomName',array(':roomName'=>$roomName));

		if(!isset($customer)) {
			$this->addError('customerName','Cliente não encontrado!');
		}
		else if(!isset($room)) {
			$this->addError('roomName','Sala não encontrada!');
		}
		else {
			$this->customerId = $customer->id;
			$this->roomId = $room->id;
		}	
	}

	public function timeValidations() {
		$this->checkTimeIsAllow();
	}

	private function checkTimeIsAllow() {
		$reservationDate = $this->bookingDate;
		$startTime = $this->startTime;
		$endTime = $this->endTime;

		$reservationAtThisDate = self::model()->findAll('bookingDate=:bookingDate',array(':bookingDate'=>$reservationDate));
		// $isStartTimeAllow = self::model()->find('startTime=:startTime',array(':startTime'=>$startTime));
		// $isEndTimeAllow = self::model()->find('endTime=:endTime',array(':endTime'=>$endTime));
		$reservationsTimes = array();
		foreach ($reservationAtThisDate as $key => $reservation) {
			$reservationStartTime = str_replace(':','',$reservation['startTime']);
			var_dump($reservationStartTime);
		}	
	}
}

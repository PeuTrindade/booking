<?php

include('libs/phpqrcode/qrlib.php');

/**
 * This is the model class for table "confirmationcodes".
 *
 * The followings are the available columns in table 'confirmationcodes':
 * @property integer $id
 * @property string $guestEmail
 * @property integer $reservationId
 * @property string $situation
 *
 * The followings are the available model relations:
 * @property Reservations $reservation
 */
class Confirmationcode extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'confirmationcodes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('reservationId', 'required'),
			array('reservationId', 'numerical', 'integerOnly'=>true),
			array('guestEmail', 'length', 'max'=>255),
			array('situation', 'length', 'max'=>55),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, guestEmail, reservationId, situation', 'safe', 'on'=>'search'),
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
			'reservation' => array(self::BELONGS_TO, 'Reservations', 'reservationId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'guestEmail' => 'Guest Email',
			'reservationId' => 'Reservation',
			'situation' => 'Situation',
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
		$criteria->compare('guestEmail',$this->guestEmail,true);
		$criteria->compare('reservationId',$this->reservationId);
		$criteria->compare('situation',$this->situation,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Confirmationcode the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function uploadCode($params,$cleanGuestEmail) {
		$folder = 'qrcodes/';
		$fileName = $cleanGuestEmail.'.png';
		$path = $folder.$fileName;
		$codeContent = 'http://localhost/booking/index.php?r=confirmationcode/index&guestEmail='.$params['guestEmail'].'&reservationId='.$params['reservationId'];
		
		QRcode::png($codeContent,$path);
	}

	public function validateCode() {
		if($this->validateCodeSituation() && $this->validateCodeByDateAndTime()) {
			$this->invalidateCode();
			return true;
		}
		else {
			return false;
		}
	}

	private function validateCodeSituation() {
		if($this->situation === 'valid')
			return true;
		else 
			return false;
	}

	private function validateCodeByDateAndTime() {
		date_default_timezone_set('America/Sao_Paulo');
		$currentDate = date('Y-m-d');
		$currentTime = date('H:i:00');

		$findReservationById = Reservation::model()->findByPk($this->reservationId);

		if($findReservationById->bookingDate === $currentDate && $findReservationById->startTime === $currentTime)
			return true;
		else 
			return false;
	}

	private function invalidateCode() {
		$this->situation = 'invalid';
		$this->save();
	}
}

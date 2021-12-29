<?php

class Room extends CActiveRecord {
	public function tableName() {
		return 'rooms';
	}

	public function rules() {
		return array(
			array('name,description,valuePerHour','required'),
			array('valuePerHour', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>55),
			array('name','checkRoomName'),
			array('image', 'required','on'=>'create'),
			array('image','file','types'=>'jpg, gif, png', 'allowEmpty'=>false,'on'=>'create'),
			array('id, name, description, image, valuePerHour', 'safe', 'on'=>'search'),
		);
	}

	public function relations() {
		return array(
			'reservations' => array(self::HAS_MANY, 'Reservations', 'roomId'),
		);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'description' => 'Description',
			'image' => 'Image',
			'valuePerHour' => 'Value Per Hour',
		);
	}

	public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['image'];
		
		return $scenarios;
    }

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('valuePerHour',$this->valuePerHour);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function beforeDelete() {
		$findReservations = Reservation::model()->findAll('roomId=:roomId',array(':roomId'=>$this->id));
		
		foreach ($findReservations as $key => $reservation) {
			$this->deleteConfirmationCodes($reservation->id);
			$reservation->delete();
		}

		return parent::beforeDelete();
	}

	private function deleteConfirmationCodes($reservationId) {
		$findConfirmationCodes = Confirmationcode::model()->findAll('reservationId=:reservationId',array(':reservationId'=>$reservationId));

		foreach ($findConfirmationCodes as $key => $confirmationCode)
			$confirmationCode->delete();
	}

	public function checkRoomName($attribute,$params) {
		$searchExpression = self::model()->find('name=:roomName',array(':roomName'=>$this->name));
			
		if(isset($searchExpression) && $searchExpression->id !== $this->id)
			$this->addError($attribute,'Esse nome já existe!');
	}

	public function beforeUploadImage($fieldFile) {
		if(isset($fieldFile))
			$this->image = $fieldFile->name;
	}

	public function uploadImage($fieldFile) {
		if(!empty($fieldFile)){
			$random = rand(0,9999);
			$fileName = $random.'-'.$fieldFile;
			$this->image = $fileName;

			$fieldFile->saveAs('uploads/'.$fileName); 
		}
	}
}

<?php
/* @var $this ReservationController */
/* @var $model Reservation */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// See class documentation of CActiveForm for details on this,
	// you need to use the performAjaxValidation()-method described there.
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'Nome do cliente'); ?>
		<?php echo $form->dropDownList($model,'customerName',$customersNames); ?>
		<?php echo $form->error($model,'customerName'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Nome da sala'); ?>
		<?php echo $form->dropDownList($model,'roomName',$roomsNames); ?>
		<?php echo $form->error($model,'roomName'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Data da reserva'); ?>
		<?php echo $form->dateField($model,'bookingDate'); ?>
		<?php echo $form->error($model,'bookingDate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Horario de inicio'); ?>
		<?php echo $form->timeField($model,'startTime'); ?>
		<?php echo $form->error($model,'startTime'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Horario de termino'); ?>
		<?php echo $form->timeField($model,'endTime'); ?>
		<?php echo $form->error($model,'endTime'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Valor total'); ?>
		<?php echo $form->textField($model,'totalAmount'); ?>
		<?php echo $form->error($model,'totalAmount'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Email dos visitantes'); ?>
		<?php echo $form->textarea($model,'guestsEmails'); ?>
		<?php echo $form->error($model,'guestsEmails'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Criar' : 'Salvar'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
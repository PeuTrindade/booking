<?php
/* @var $this ReservationController */
/* @var $model Reservation */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'Nome do cliente'); ?>
		<?php echo $form->dropDownList($model,'customerId',$customersNames); ?>
		<?php echo $form->error($model,'customerId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Nome da sala'); ?>
		<?php echo $form->dropDownList($model,'roomId',$roomsNames); ?>
		<?php echo $form->error($model,'roomId'); ?>
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

</div>
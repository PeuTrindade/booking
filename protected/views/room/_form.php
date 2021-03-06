<?php ?>

<div class='form'>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'form',
	'htmlOptions'=>array(
		'enctype'=>'multipart/form-data'
	),
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class='row'>
		<?php echo $form->labelEx($model,'Nome da sala'); ?>
		<?php echo $form->textField($model,'name'); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div id='imageRow' class='row'>
		<input id='imageInfo' value='<?php echo $model->image; ?>' type='hidden'/>
		<?php echo $form->labelEx($model,'image'); ?>
		<?php echo $form->fileField($model,'image'); ?>
		<?php echo $form->error($model,'image'); ?>
	</div>

	<div class='row'>
		<?php echo $form->labelEx($model,'Descricao'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class='row'>
		<?php echo $form->labelEx($model,'Valor por hora'); ?>
		<?php echo $form->textField($model,'valuePerHour'); ?>
		<?php echo $form->error($model,'valuePerHour'); ?>
	</div>

	<div class='row buttons'>
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Criar sala' : 'Atualizar sala'); ?>
	</div>

<?php $this->endWidget(); ?>

</div>
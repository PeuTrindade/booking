<?php

Yii::app()->name = 'Booking';
$this->pageTitle = Yii::app()->name . ' - Login';

?>

<style>
	<?php include 'css/login/login.css'; ?>
</style>

<section class='loginContainer'>
	<div class='loginInformation'>
		<h1>Faça o seu login</h1>
		<div class='form'>
			<?php $form = $this->beginWidget('CActiveForm', array(
				'id'=>'login-form',
				'enableClientValidation'=>true,
				'clientOptions'=>array(
					'validateOnSubmit'=>true,
				),
			)); ?>
			<div class='row'>
				<?php echo $form->labelEx($model,'Usuario'); ?>
				<?php echo $form->textField($model,'username'); ?>
				<?php echo $form->error($model,'username'); ?>
			</div>
			<div class='row'>
				<?php echo $form->labelEx($model,'Senha'); ?>
				<?php echo $form->passwordField($model,'password'); ?>
				<?php echo $form->error($model,'password'); ?>
			</div>
			<div class='row buttons'>
				<?php echo CHtml::submitButton('Login'); ?>
			</div>
			<?php $this->endWidget(); ?>
		</div>
	</div>
	<img src='images/loginImage.jpg' alt='Imagem página de login'/>
</section>

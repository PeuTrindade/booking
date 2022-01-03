<?php  

Yii::app()->name = 'Booking';
$this->pageTitle = Yii::app()->name . ' - Criar sala';

?>

<style>
    <?php include 'css/room/roomCreate.css'; ?>
</style>

<section class='createRoomContainer'>
    <h1>Crie uma sala</h1>
    <p>Preencha os campos abaixo</p>
    <?php $this->renderPartial('_form',array('model'=>$model)); ?>
</section>
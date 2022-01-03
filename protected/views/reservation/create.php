<?php 

Yii::app()->name = 'Booking';
$this->pageTitle = Yii::app()->name . ' - Criar reserva';

?>

<style>
    <?php include 'css/reservation/reservationCreate.css'; ?>
</style>

<section class='createReservationContainer'>
    <h1>Crie uma reserva</h1>
    <p>Preencha os campos abaixo</p>
    <?php $this->renderPartial('_form',array('model'=>$model,'customersNames'=>$customersNames,'roomsNames'=>$roomsNames)); ?>

</section>
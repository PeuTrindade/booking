<?php

Yii::app()->name = 'Booking';
$this->pageTitle = Yii::app()->name . ' - Atualizar reserva';

?>

<style>
    <?php include 'css/customer/customerUpdate.css'; ?>
</style>

<section class='updateCustomerContainer'>
    <h1>Atualize uma reserva</h1>
    <p>Modifique as informações desejadas</p>
    <?php $this->renderPartial('_form',array('model'=>$model,'customersNames'=>$customersNames,'roomsNames'=>$roomsNames)); ?>
</section>
<?php ?>

<style>
    <?php include 'css/reservation/reservationCreate.css'; ?>
</style>

<section class='createReservationContainer'>
    <h1>Crie uma reserva</h1>
    <p>Preencha os campos abaixo</p>
    <?php $this->renderPartial('_form',array('model'=>$model)); ?>
</section>
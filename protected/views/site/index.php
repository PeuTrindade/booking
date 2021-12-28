<?php

Yii::app()->name = 'Booking';
$this->pageTitle = Yii::app()->name . ' - Home';

?>

<style>
    <?php include 'css/homePage/homePage.css'; ?>
</style>

<section class='homeContainer'>
    <h1>Bem vindo(a), <?php echo Yii::app()->user->name; ?>!</h1>
    <p>Gerencie o sistema de agendamento</p>
</section>

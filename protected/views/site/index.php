<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<style>
    <?php include "css/homePage/homePage.css"; ?>
</style>

<section class="homeContainer">
    <h1>Bem vindo(a), <?php echo Yii::app()->user->name; ?>!</h1>
    <p>Genrencie o sistema de agendamento</p>
</section>

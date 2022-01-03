<?php  

Yii::app()->name = 'Booking';
$this->pageTitle = Yii::app()->name . ' - Criar cliente';

?>

<style>
    <?php include 'css/customer/customerCreate.css'; ?>
</style>

<section class='createCustomerContainer'>
    <h1>Crie um cliente</h1>
    <p>Preencha os campos abaixo</p>
    <?php $this->renderPartial('_form',array('model'=>$model)); ?>
</section>
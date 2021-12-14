<?php

?>

<style>
    <?php include 'css/customer/customerUpdate.css'; ?>
</style>

<section class='updateCustomerContainer'>
    <h1>Atualize um cliente</h1>
    <p>Modifique as informações desejadas</p>
    <?php $this->renderPartial('_form',array('model'=>$model)); ?>
</section>
<?php  ?>

<style>
    <?php include 'css/room/roomUpdate.css'; ?>
</style>

<section class='updateRoomContainer'>
    <h1>Atualize uma sala</h1>
    <p>Modifique as informações desejadas</p>
    <?php $this->renderPartial('_form',array('model'=>$model)); ?>
</section>
<?php

$id = CHtml::encode($data->id);
$customerId = CHtml::encode($data->customerId);
$roomId = CHtml::encode($data->roomId);

?>

<style>
    <?php include 'css/reservation/reservations.css'; ?>
</style>

<a class='reservation' href='<?php echo $this->createUrl('reservation/view',array('id'=>$id)); ?>'>ID da reserva: <?php echo $id; ?> | ID do cliente: <?php echo $customerId;  ?> | ID da sala: <?php echo $roomId; ?></a>
<?php
$id = CHtml::encode($data->id);
$customerName = CHtml::encode($data->customerName);
$roomName = CHtml::encode($data->roomName);

if(strlen($customerName) > 20){
    $customerName = substr($customerName,0,20).'...';
}
?>

<style>
    <?php include 'css/reservation/reservations.css'; ?>
</style>

<a class='reservation' href='<?php echo $this->createUrl('reservation/view',array('id'=>$id)); ?>'>ID da reserva: <?php echo $id; ?> | Cliente: <?php echo $customerName;  ?> | Sala: <?php echo $roomName; ?></a>
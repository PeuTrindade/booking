<?php
$id = CHtml::encode($data->id);
$customerName = CHtml::encode($data->customerName);
$roomName = CHtml::encode($data->roomName);
$bookingDate = CHtml::encode($data->bookingDate);

if(strlen($customerName) > 20){
    $customerName = substr($customerName,0,20).'...';
}
?>

<style>
    <?php include 'css/reservation/reservations.css'; ?>
</style>

<a class='reservation' href='index.php?r=reservation/view&id=<?php echo $id; ?>'>ID da reserva: <?php echo $id; ?> | Data da reserva: <?php echo $bookingDate; ?> | Cliente: <?php echo $customerName  ?> | Sala: <?php echo $roomName ?></a>
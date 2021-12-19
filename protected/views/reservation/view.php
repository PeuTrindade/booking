<?php 

$id = $model->id;
$customerName = $model->customerName;
$roomName = $model->roomName;
$bookingDate = $model->bookingDate;
$startTime = $model->startTime;
$endTime = $model->endTime;
$totalAmount = $model->totalAmount;
$guestsEmails = $model->guestsEmails;

?>

<style>
    <?php include 'css/reservation/reservationView.css'; ?>
</style>

<section class='viewReservationContainer'>
    <h1>Reserva <?php echo $id; ?></h1>
    <div class='viewReservationInfo'>
        <h3>Nome do cliente: <?php echo $customerName; ?></h3>
        <h3>Nome da sala: <?php echo $roomName; ?></h3>
        <h3>Data de reserva: <?php echo $bookingDate; ?></h3>
        <h3>Horário de início: <?php echo $startTime; ?></h3>
        <h3>Horário de término: <?php echo $endTime; ?></h3>
        <h3>Valor total: <?php echo $totalAmount; ?></h3>
        <h3>Emails dos visitantes: <?php echo $guestsEmails; ?></h3>
    </div>
    <div class='viewReservationControls'>
        <a href='<?php echo $this->createUrl('reservation/update',array('id'=>$id)); ?>'>Atualizar reserva</a>
        <a href='<?php echo $this->createUrl('reservation/delete',array('id'=>$id)); ?>'>Deletar reserva</a>
    </div>
</section>
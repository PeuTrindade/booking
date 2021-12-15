<?php 

$id = $model->id;
$name = $model->name;
$image = $model->image;
$description = $model->description;
$valuePerHour = $model->valuePerHour;

?>

<style>
    <?php include 'css/room/roomView.css'; ?>
</style>

<section class='viewRoomContainer'>
    <img src='uploads/<?php echo $image; ?>' alt='Imagem da <?php echo $name;?>'>
    <h1><?php echo $name; ?></h1>
    <h4>ID da sala: <?php echo $id; ?></h4>
    <h4>Valor por hora: R$<?php echo $valuePerHour; ?></h4>
    <p><?php echo $description; ?></p>
</section>
<?php 
$id = CHtml::encode($data->id);
$name = CHtml::encode($data->name);
$image = CHtml::encode($data->image);
?>

<style>
    <?php include 'css/room/room.css'; ?>
</style>

<div class='room'>
    <img src=<?php echo $image; ?> alt='Imagem da <?php echo $name; ?>'>
    <h3><?php echo $name; ?></h3>
    <h4>ID da sala: <?php echo $id; ?></h4>
    <div class='roomLinks'>
        <a href='index.php?r=room/update&id=<?php echo $id; ?>'>Atualizar sala</a>
        <a href='index.php?r=room/deleteRoom&id=<?php echo $id; ?>'>Deletar sala</a>
    </div>
</div>
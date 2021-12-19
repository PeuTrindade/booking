<?php 
$id = CHtml::encode($data->id);
$name = CHtml::encode($data->name);
$image = CHtml::encode($data->image);
?>

<style>
    <?php include 'css/room/room.css'; ?>
</style>

<div class='room'>
    <img src='uploads/<?php echo $image; ?>' alt='Imagem da <?php echo $name; ?>'>
    <h3><a class='redirectLink' href='<?php echo $this->createUrl('room/view',array('id'=>$id)); ?>'><?php echo $name; ?></a></h3>
    <h4>ID da sala: <?php echo $id; ?></h4>
    <div class='roomLinks'>
        <a href='<?php echo $this->createUrl('room/update',array('id'=>$id)); ?>'>Atualizar sala</a>
        <a href='<?php echo $this->createUrl('room/delete',array('id'=>$id)); ?>'>Deletar sala</a>
    </div>
</div>
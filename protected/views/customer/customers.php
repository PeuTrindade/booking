<?php
$id = CHtml::encode($data->id);
$name = CHtml::encode($data->name);
$personCode = CHtml::encode($data->personCode);

if(strlen($name) > 20){
    $name = substr($name,0,20).'...';
}
?>

<style>
    <?php include 'css/customer/customers.css'; ?>
</style>

<a class='customer' href='index.php?r=customer/view&id=<?php echo $id; ?>'>ID do cliente: <?php echo $id; ?> | Nome: <?php echo $name; ?> | CPF/CNPJ: <?php echo $personCode; ?></a>
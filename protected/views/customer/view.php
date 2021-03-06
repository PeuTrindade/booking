<?php
$id = $model->id;
$name = $model->name;
$email = $model->email;
$personCode = $model->personCode;
$phoneNumber = $model->phoneNumber;
$birthday = $model->birthday;

Yii::app()->name = 'Booking';
$this->pageTitle = Yii::app()->name . ' - Cliente '.$id;

?>

<style>
    <?php include 'css/customer/customerView.css'; ?>
</style>

<section class='viewCustomerContainer'>
    <h1>Cliente <?php echo $name; ?></h1>
    <div class='viewCustomerInfo'>
        <h3>ID do cliente: <?php echo $id; ?></h3>
        <h3>Email do cliente: <?php echo $email; ?></h3>
        <h3>CPF/CNPJ do cliente: <?php echo $personCode; ?></h3>
        <h3>Telefone do cliente: <?php echo $phoneNumber; ?></h3>
        <h3>Nascimento do cliente: <?php echo $birthday; ?></h3>
    </div>
    <div class='viewCustomerControls'>
        <a href='<?php echo $this->createUrl('customer/update',array('id'=>$id)); ?>'>Atualizar cliente</a>
        <a href='<?php echo $this->createUrl('customer/delete',array('id'=>$id)); ?>'>Deletar cliente</a>
    </div>
</section>


<?php 

Yii::app()->name = 'Booking';
$this->pageTitle = Yii::app()->name . ' - Validar QRcode';

?>

<section class='confirmationcodeContainer'>
    <?php   
        if($isValid)
            $this->renderPartial('isValid');
        else 
            $this->renderPartial('isNotValid');
    ?>
</section>

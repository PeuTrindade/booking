<?php ?>

<section class='confirmationcodeContainer'>
    <?php   
        if($isValid)
            $this->renderPartial('isValid');
        else 
            $this->renderPartial('isNotValid');
    ?>
</section>

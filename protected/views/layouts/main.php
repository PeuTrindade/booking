<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="language" content="pt-br">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/sidebar/sidebar.css"></link>
	<link rel="stylesheet" href="css/main/main.css"></link>
	<link rel="stylesheet" href="css/form/form.css"></link>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css">
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>
<body>
	<main class="mainContainer">
		<?php if(isset(Yii::app()->user->id)){ ?>
		<section class="sidebarContainer">
			<h3>Painel</h3>
			<nav class="sidebar">
				<?php $this->widget('zii.widgets.CMenu',array(
					'id'=>'sidebarItems',
					'items'=>array(
						array('label'=>'Home', 'url'=>array('/site/index')),
						array('label'=>'Clientes', 'url'=>array('/customer/index')),
						array('label'=>'Salas', 'url'=>array('/room/index')),
						array('label'=>'Reservas', 'url'=>array('/reservation/index')),
						array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
					),
				)); ?>
			</nav>
		</section>
		<?php } ?>
		<?php echo $content; ?>
	</main>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src='js/uploadImage.js'></script>
	<script src='js/reservationForm.js'></script>
</body>
</html>

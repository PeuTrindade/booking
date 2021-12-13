<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="language" content="pt-br">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/sidebar/sidebar.css"></link>
	<link rel="stylesheet" href="css/main/main.css"></link>
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
					'items'=>array(
						array('label'=>'Home', 'url'=>array('/site/index')),
						array('label'=>'Clientes', 'url'=>array('/site/page', 'view'=>'about')),
						array('label'=>'Salas', 'url'=>array('/site/page', 'view'=>'about')),
						array('label'=>'Reservas', 'url'=>array('/site/page', 'view'=>'about')),
						array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
					),
				)); ?>
			</nav>
		</section>
		<?php } ?>
		<?php echo $content; ?>
	</main>
</body>
</html>

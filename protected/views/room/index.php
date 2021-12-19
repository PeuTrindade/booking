<?php
/* @var $this RoomController */

?>

<style>
	<?php include 'css/room/roomIndex.css'; ?>
</style>

<section class='roomPageContainer'>
	<h1>Salas</h1>
	<p>Gerencie as salas do sistema</p>
	<div id='rooms' class='rooms'>
		<?php
			$this->widget('zii.widgets.CListView', array(
				'dataProvider'=>$dataProvider,
				'itemView'=>'rooms',
				'template'=>"{items}\n{pager}",
				'pager'=> array(
					'header'=>'',
					'prevPageLabel' => 'Anterior',
        			'nextPageLabel' => 'Próximo',
				)
			));
		?>
	</div>
	<a class='roomCreateButton' href='<?php echo $this->createUrl('room/create'); ?>'>Crie uma sala</a>
</section>

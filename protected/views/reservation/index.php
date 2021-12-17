<?php
/* @var $this ReservationController */
?>

<style>
	<?php include 'css/reservation/reservationIndex.css'; ?>
</style>

<section class='reservationPageContainer'>
	<h1>Reservas</h1>
	<p>Gerencie as reservas do sistema</p>
	<div class='reservations'>
		<?php
			$this->widget('zii.widgets.CListView', array(
				'dataProvider'=>$dataProvider,
				'itemView'=>'reservations',
				'template'=>"{items}\n{pager}",
				'pager'=> array(
					'header'=>'',
					'prevPageLabel' => 'Anterior',
        			'nextPageLabel' => 'Próximo',
				)
			));
		?>
	</div>
	<a class='reservationCreateButton' href='index.php?r=reservation/create'>Crie uma reserva</a>
</section>
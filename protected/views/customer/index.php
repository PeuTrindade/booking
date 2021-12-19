<?php
/* @var $this CustomerController */

?>

<style>
	<?php include 'css/customer/customerIndex.css'; ?>
</style>

<section class='customerContainer'>
	<h1>Clientes</h1>
	<p>Gerencie os clientes do sistema</p>
	<?php
		$this->widget('zii.widgets.CListView', array(
			'dataProvider'=>$dataProvider,
			'itemView'=>'customers',
			'template'=>"{items}\n{pager}",
			'pager'=> array(
				'header'=>'',
				'prevPageLabel' => 'Anterior',
        		'nextPageLabel' => 'Próximo',
			)
		));
	?>
	<a class='customerCreateButton' href='<?php echo $this->createUrl('customer/create'); ?>'>Crie um cliente</a>
</section>


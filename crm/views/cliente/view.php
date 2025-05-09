<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\console\widgets\Table;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Cliente */

$this->title = $model->cliente_id.": ".$model->nombre." ".$model->prim_ap;
$this->params['breadcrumbs'][] = ['label' => 'Clientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="cliente-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
		<?php 	
			if($modif){
		?>
			<?= Html::a('Actualizar', ['update', 'id' => $model->cliente_id], ['class' => 'btn btn-primary']) ?>
		<?php } ?>
		<?= Html::a('Clientes', ['index'], ['class' => 'btn btn-secondary']) ?>
    </p>
	
	<hr></hr>
	<h3>Etapa y Estatus</h3>
	<hr></hr>
	
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
			[ 'label'  => 'Etapa', 'value'  => $estatus_cliente["etapa"]],
            [ 'label'  => 'Estatus', 'value'  => $estatus_cliente["estatus"]],
            'otro_estatus',			
		]
	]) ?>
	
	<hr></hr>
	<h3>Cliente</h3>
	<hr></hr>
	
	<?= DetailView::widget([
        'model' => $model,
        'attributes' => [
		
            'cliente_id',
            'nombre',
            'prim_ap',
            'seg_ap',
	]]) ?>
	
	<hr></hr>
	<h3>Cónyuge</h3>
	<hr></hr>
	
	<?= DetailView::widget([
        'model' => $model,
        'attributes' => [
			
            'cony_nombre',
            'cony_prim_ap',
            'cony_seg_ap',
	]]) ?>
	
	<hr></hr>
	<h3>Desarrollo</h3>
	<hr></hr>
	
	<?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            
			'num_int',
            'vcv',
            'monto_credito',
            'comentarios:ntext',
	]]) ?>
	
	<hr></hr>
	<h3>Equipo</h3>
	<hr></hr>
	
	<?= DetailView::widget([
			'model' => $model,
			'attributes' => [

				[ 'label'  => 'Asesor', 'value'  => $model["asesor0"]["nombre"]." ".$model["asesor0"]["apellido"]." (".$model["asesor0"]["email"].")"],
				[ 'label'  => 'Gerente', 'value'  => $model["gerente0"]["nombre"]." ".$model["gerente0"]["apellido"]." (".$model["gerente0"]["email"].")"],
				[ 'label'  => 'Director Hipotecario', 'value'  => $model["directorHipotecario"]["nombre"]." ".$model["directorHipotecario"]["apellido"]." (".$model["directorHipotecario"]["email"].")"],
			]
		]) ?>
	
	<hr></hr>
	<h3>Historial</h3>

	
	<table  class="table table-striped">
		<tr><th>Estatus</th><th>Fecha</th><th>Asignado Por</th></tr>
		
	<?php	
		foreach($historial as $h){
			$ot=$h["otro"]!=null?"--".$h["otro"]:"";
	?>
	<?=
		'<tr><td>'.$ecal[$h["estatus_cliente_id"]].''.$ot.'</td><td>'.$h["fecha"].'</td><td>'.$h["usuario0"]["nombre"].' '.$h["usuario0"]["apellido"].' ('.$h["usuario0"]["email"].')</td></tr>'
	?>
	<?php
		};
	?>
	</table>
	
</div>

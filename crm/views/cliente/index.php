<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ClienteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Clientes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cliente-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
		<?php 
			$temp='{view}';
			if($modif){
				$temp='{view} {update}';
		?>
			<?= Html::a('Nuevo Cliente', ['create'], ['class' => 'btn btn-success']) ?>
		<?php }
		
		$columnas=[
            //['class' => 'yii\grid\SerialColumn'],
            'cliente_id',
            'nombre',
            'prim_ap',
            'seg_ap',
			
            [
				'label'=>'Director',
				'attribute'=>'directorHipotecario',				
				'value' => function ($data) {
					return $data["directorHipotecario"]["nombre"]." ".$data["directorHipotecario"]["apellido"]." (".$data["directorHipotecario"]["email"].")";
				},
				'filter'=>$dil,
			],
			
            [
				'label'=>'Gerente',
				'attribute'=>'gerente0',				
				'value' => function ($data) {
					return $data["gerente0"]["nombre"]." ".$data["gerente0"]["apellido"]." (".$data["gerente0"]["email"].")";
				},
				'filter'=>$gil,
			],
			
            [
				'label'=>'Asesor',
				'attribute'=>'asesor0',
				//'value' => 'asesor0.nombre'
				'value' => function ($data) {
					return $data["asesor0"]["nombre"]." ".$data["asesor0"]["apellido"]." (".$data["asesor0"]["email"].")";
				},
				'filter'=>$ail,
			],
						
            'num_int',
            'vcv',
            [
				'label'=>'Estatus',
				'attribute'=>'estatusCliente',
				'value' => function ($data)use ($ecal) {
					$ot=$data["otro_estatus"]!=null?"--".$data["otro_estatus"]:"";
					return $ecal[$data["estatus_cliente_id"]]."".$ot;
				},
				'filter'=>$ecal,
			],

           ['class' => 'yii\grid\ActionColumn','template'=>$temp],
        ];
		
		?>
    </p>

 <?=  ExportMenu::widget([
    'dataProvider' => $dataProvider,
    'columns' => $columnas,
	'exportConfig' => [
		ExportMenu::FORMAT_CSV => false,
        ExportMenu::FORMAT_TEXT => false,
        ExportMenu::FORMAT_HTML => false,
        ExportMenu::FORMAT_EXCEL => false,
        ExportMenu::FORMAT_PDF => false,
    ],
	
]);
?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columnas,
    ]); ?>


</div>

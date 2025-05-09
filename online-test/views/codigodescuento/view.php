<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Codigodescuento */

$this->title = "Detalle de Uso de Código ".$model->codigodescuento_id;
$this->params['breadcrumbs'][] = ['label' => 'Codigodescuentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="codigodescuento-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
			["label"=>"ID","attribute"=>'codigodescuento_id'],
			["label"=>"Código Descuento","attribute"=>'codigodescuento'],
			["label"=>"Porcentaje de Descuento","attribute"=>'porcentaje'],
			["label"=>"Fecha Inicio","attribute"=>'fechainicio'],
			["label"=>"Fecha Fin","attribute"=>'fechafin'],
			["label"=>"Estatus","attribute"=>'estatus'],
        ],
    ]) ?>
	
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
			["label"=>"Usuario","attribute"=>'usuario'],
			["label"=>"Fecha de Uso","attribute"=>'fechauso'],
			["label"=>"Pago Total","attribute"=>'pagototal'],
			["label"=>"Estatus","attribute"=>'estatus'],
        ],
    ]); ?>
	

</div>

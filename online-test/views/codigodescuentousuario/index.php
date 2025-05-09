<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CodigodescuentousuarioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Códigos de Descuento por Usuario';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="codigodescuentousuario-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Nuevo Código', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

			["label"=>"ID","attribute"=>"codigodescuentousuario_id"],
			["label"=>"Usuario","attribute"=>"usuario"],
			["label"=>"Enviado","attribute"=>"fechaenvio"],
			["label"=>"Tipo","attribute"=>"tipo"],
			["label"=>"Porcentaje","attribute"=>"porcentaje"],
			["label"=>"Inicio","attribute"=>"fechainicio"],
			["label"=>"Fin","attribute"=>"fechafin"],
			["label"=>"Pago","attribute"=>"pagototal"],
			["label"=>"Estatus","attribute"=>"estatus"],

        ],
    ]); ?>


</div>

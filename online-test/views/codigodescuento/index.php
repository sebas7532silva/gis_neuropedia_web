<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CodigodescuentoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Códigos de Descuento Multiuso';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="codigodescuento-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Nuevo Código', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

			["label"=>"ID","attribute"=>"codigodescuento_id"],
			["label"=>"Código","attribute"=>"codigodescuento"],
			["label"=>"Porcentaje","attribute"=>"porcentaje"],
			["label"=>"Fecha Inicio","attribute"=>"fechainicio"],
			["label"=>"Fecha Fin","attribute"=>"fechafin"],
			["label"=>"Estatus","attribute"=>"estatus"],

            ['class' => 'yii\grid\ActionColumn',
				'template' => '{update} {view}',
			],
        ],
    ]); ?>


</div>

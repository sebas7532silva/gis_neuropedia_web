<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RecursoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Recursos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recurso-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Cargar Recurso', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'nombre',
            [
				'label'=>'Archivo',
				'attribute'=>'archivo',
                'format' => 'raw',
                'value' => function ($data) {
                    $url = "files/".$data->archivo;
                    return Html::a('<i class="glyphicon glyphicon-download-alt"></i>', $url,  ['target'=>'_blank']);
                },

			],       
        ],
    ]); ?>


</div>

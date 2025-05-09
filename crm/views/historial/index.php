<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\HistorialSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Historials';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="historial-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Historial', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'historial_id',
            'estatus_cliente_id',
            'fecha',
            'usuario',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>

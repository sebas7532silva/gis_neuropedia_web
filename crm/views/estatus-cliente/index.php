<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EstatusClienteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Estatus Clientes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="estatus-cliente-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Estatus Cliente', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'estatus_cliente_id',
            'estatus',
            'etapa',
            'activo',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>

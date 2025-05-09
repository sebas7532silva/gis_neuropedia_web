<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CodigodescuentologSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Codigodescuentologs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="codigodescuentolog-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Codigodescuentolog', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'codigodescuentolog',
            'codigodescuento_id',
            'usuario',
            'fechauso',
            'pagototal',
            //'estatus',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>

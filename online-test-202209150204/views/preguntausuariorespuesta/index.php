<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PreguntausuariorespuestaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Preguntausuariorespuestas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="preguntausuariorespuesta-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Preguntausuariorespuesta', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'respuesta_id',
            'pregunta_id',
            'usuario_id',
            'respuesta',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>

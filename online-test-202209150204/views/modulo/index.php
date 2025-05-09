<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ModuloSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sesiones de '.$curso->titulo;
$this->params['breadcrumbs'][] = $this->title;
$temp='{update}';

?>
<div class="modulo-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    <?= Html::a('Nueva Sesión', ['create','curso_id' =>Yii::$app->request->get('curso_id')], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'titulo',
            'video',

            ['class' => 'yii\grid\ActionColumn','template'=>$temp],
        ],
    ]); ?>


</div>

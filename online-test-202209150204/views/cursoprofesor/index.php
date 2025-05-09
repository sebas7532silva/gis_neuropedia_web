<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CursoprofesorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Asignar Módulo a Profesor';
$this->params['breadcrumbs'][] = $this->title;
$temp = "{view} {update}";
?>
<div class="cursoprofesor-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Asignar', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'curso.titulo',
            'email',
            'titularidad',

            ['class' => 'yii\grid\ActionColumn',"template"=>$temp],
        ],
    ]); ?>


</div>

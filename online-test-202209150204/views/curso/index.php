<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CursoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Módulo';
$this->params['breadcrumbs'][] = $this->title;
$temp="{view} {update}";
?>
<div class="curso-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Nuevo Módulo', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'titulo',
            //'descripcion:ntext',
            'tipo.nombre',
            'sesiones',
            'horas',
            //'presentacion:ntext',
            //'objetivos:ntext',
            //'contenido:ntext',
            //'unidades:ntext',
            //'acreditacion:ntext',
            //'bibliografia:ntext',

            ['class' => 'yii\grid\ActionColumn', "template"=>$temp],
        ],
    ]); ?>


</div>

<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ModulomaterialSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Materiales';
$this->params['breadcrumbs'][] = $this->title;
$temp="{view}";
?>
<div class="modulomaterial-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Cargar Material', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'material',
            'filename',

            ['class' => 'yii\grid\ActionColumn', "template"=>$temp],
        ],
    ]); ?>


</div>

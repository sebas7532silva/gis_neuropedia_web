<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DescargaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Descargas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="descarga-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
				'label'=>'Nombre y Apellido',
				'attribute'=>'nombreapellido',
			],            'email:email',
            'telefono',
            'ip',
            //'fecha',
            //'data',

        ],
    ]); ?>


</div>

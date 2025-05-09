<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $searchModel app\models\UsuarioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Usuarios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usuario-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Nuevo Usuario', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'emptyText' => 'Ningún resultado.',

        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'email:email',
            //'password',
            'nombre',
            'apellido',
            //'telefono',
            //'estatus',
            [
				'label'=>'Perfil',
				'attribute'=>'perfil',
				'value' => 'perfil',
				'filter'=>['DIRECCION GENERAL' => 'DIRECCION GENERAL'],
			],
			
            ['class' => 'yii\grid\ActionColumn','template'=>'{view} {update}'],
        ],
    ]); ?>


</div>
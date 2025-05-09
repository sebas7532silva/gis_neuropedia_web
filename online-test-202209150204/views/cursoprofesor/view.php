<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Cursoprofesor */

$this->title = $model->curso->titulo." asignado a ".$model->email;
$this->params['breadcrumbs'][] = ['label' => 'Cursoprofesors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="cursoprofesor-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Modificar', ['update', 'curso_id' => $model->curso_id, 'email' => $model->email], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'curso_id' => $model->curso_id, 'email' => $model->email], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Quitar a este profesor de este curso?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'curso.titulo',
            'email',
            'titularidad',
        ],
    ]) ?>

</div>

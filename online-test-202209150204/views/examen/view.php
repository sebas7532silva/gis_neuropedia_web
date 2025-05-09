<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Examen */

$this->title = $model->examen_id;
$this->params['breadcrumbs'][] = ['label' => 'Examenes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="examen-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar Preguntas', ['update', 'id' => $model->examen_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Deshabilitar', ['delete', 'id' => $model->examen_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'examen_id',
            'titulo',
            'descripcion:ntext',
        ],
    ]) ?>

</div>

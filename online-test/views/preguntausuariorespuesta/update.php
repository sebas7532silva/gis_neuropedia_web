<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Preguntausuariorespuesta */

$this->title = 'Update Preguntausuariorespuesta: ' . $model->respuesta_id;
$this->params['breadcrumbs'][] = ['label' => 'Preguntausuariorespuestas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->respuesta_id, 'url' => ['view', 'id' => $model->respuesta_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="preguntausuariorespuesta-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

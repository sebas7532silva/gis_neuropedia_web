<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Preguntas */

$this->title = 'Update Preguntas: ' . $model->pregunta_id;
$this->params['breadcrumbs'][] = ['label' => 'Preguntas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pregunta_id, 'url' => ['view', 'id' => $model->pregunta_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="preguntas-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

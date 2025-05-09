<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Examen */

$this->title = 'Actualizar Examen: ' . $model->examen_id;
$this->params['breadcrumbs'][] = ['label' => 'Examenes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->examen_id, 'url' => ['view', 'id' => $model->examen_id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="examen-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Examenpregunta */

$this->title = 'Modificar';
$this->params['breadcrumbs'][] = ['label' => 'Examenpreguntas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pregunta_id, 'url' => ['view', 'id' => $model->pregunta_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="examenpregunta-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

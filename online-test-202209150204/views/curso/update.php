<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Curso */

$this->title = 'Modificar Módulo: ' . $model->titulo;
$this->params['breadcrumbs'][] = ['label' => 'Cursos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->curso_id, 'url' => ['view', 'id' => $model->curso_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="curso-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'tcl' => $tcl,
    ]) ?>

</div>

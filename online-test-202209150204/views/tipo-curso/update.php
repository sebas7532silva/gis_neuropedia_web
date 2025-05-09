<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TipoCurso */

$this->title = 'Modificar Tipo de Módulo: ' . $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Tipo Cursos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->tipo_id, 'url' => ['view', 'id' => $model->tipo_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tipo-curso-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

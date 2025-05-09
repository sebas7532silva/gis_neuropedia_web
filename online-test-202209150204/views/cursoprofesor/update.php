<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Cursoprofesor */

$this->title = 'Modificar Asignación: ' . $model->curso->titulo." asignado a ".$model->email;
$this->params['breadcrumbs'][] = ['label' => 'Cursoprofesors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->curso_id, 'url' => ['view', 'curso_id' => $model->curso_id, 'email' => $model->email]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cursoprofesor-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'tproflist' => $tproflist,
        'tcursolist' => $tcursolist,        
    ]) ?>

</div>

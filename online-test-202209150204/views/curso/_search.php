<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CursoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="curso-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'curso_id') ?>

    <?= $form->field($model, 'titulo') ?>

    <?= $form->field($model, 'descripcion') ?>

    <?= $form->field($model, 'tipo_id') ?>

    <?= $form->field($model, 'ubicacion') ?>

    <?php // echo $form->field($model, 'sesiones') ?>

    <?php // echo $form->field($model, 'horas') ?>

    <?php // echo $form->field($model, 'presentacion') ?>

    <?php // echo $form->field($model, 'objetivos') ?>

    <?php // echo $form->field($model, 'contenido') ?>

    <?php // echo $form->field($model, 'unidades') ?>

    <?php // echo $form->field($model, 'acreditacion') ?>

    <?php // echo $form->field($model, 'bibliografia') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

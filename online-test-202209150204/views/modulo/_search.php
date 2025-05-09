<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ModuloSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="modulo-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'modulo_id') ?>

    <?= $form->field($model, 'titulo') ?>

    <?= $form->field($model, 'video') ?>

    <?= $form->field($model, 'curso_id') ?>

    <?= $form->field($model, 'ejercicios') ?>

    <?php // echo $form->field($model, 'horas_practicas') ?>

    <?php // echo $form->field($model, 'horas_teoricas') ?>

    <?php // echo $form->field($model, 'usuario_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

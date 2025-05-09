<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CodigodescuentousuarioSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="codigodescuentousuario-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'codigodescuentousuario_id') ?>

    <?= $form->field($model, 'usuario') ?>

    <?= $form->field($model, 'fechaenvio') ?>

    <?= $form->field($model, 'tipo') ?>

    <?= $form->field($model, 'porcentaje') ?>

    <?php // echo $form->field($model, 'fechainicio') ?>

    <?php // echo $form->field($model, 'fechafin') ?>

    <?php // echo $form->field($model, 'pagototal') ?>

    <?php // echo $form->field($model, 'estatus') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

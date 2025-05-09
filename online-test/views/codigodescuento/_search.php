<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CodigodescuentoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="codigodescuento-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'codigodescuento_id') ?>

    <?= $form->field($model, 'codigodescuento') ?>

    <?= $form->field($model, 'porcentaje') ?>

    <?= $form->field($model, 'fechainicio') ?>

    <?= $form->field($model, 'fechafin') ?>

    <?php // echo $form->field($model, 'estatus') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

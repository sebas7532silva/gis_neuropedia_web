<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Codigodescuentolog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="codigodescuentolog-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'codigodescuento_id')->textInput() ?>

    <?= $form->field($model, 'usuario')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fechauso')->textInput() ?>

    <?= $form->field($model, 'pagototal')->textInput() ?>

    <?= $form->field($model, 'estatus')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

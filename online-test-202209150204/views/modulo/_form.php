<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Modulo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="modulo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'titulo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'video')->textInput(['maxlength' => true]) ?>

    <div style="display:none">
        <?= $form->field($model, 'curso_id')->hiddenInput(['value' =>  Yii::$app->request->get('curso_id')]) ?>
    </div>

    <?= $form->field($model, 'ejercicios')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'horas_practicas')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'horas_teoricas')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'usuario_id')->dropDownList($tproflist,['prompt'=>'--Seleccionar--'])->Label('Profesor') ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Curso */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="curso-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'titulo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descripcion')->textarea(['rows' => 6]) ?>

    <?=$form->field($model, 'tipo_id')->dropDownList($tcl,['prompt'=>'--Seleccionar--']) ?>

    <?= $form->field($model, 'ubicacion')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'sesiones')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'horas')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'presentacion')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'objetivos')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'contenido')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'unidades')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'acreditacion')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'bibliografia')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

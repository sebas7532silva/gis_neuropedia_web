<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Usuario */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="usuario-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true,'disabled'=>$keyconst]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'apellido')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telefono')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'perfil')->dropDownList(
            ['DIRECCION GENERAL' => 'DIRECCION GENERAL', 'MESA DE CONTROL' => 'MESA DE CONTROL', 'DIRECTOR HIPOTECARIO' => 'DIRECTOR HIPOTECARIO', 'ASESOR INMOBILIARIO' => 'ASESOR INMOBILIARIO', 'GERENTE INMOBILIARIO' => 'GERENTE INMOBILIARIO', 'GERENTE JURIDICO' => 'GERENTE JURIDICO']
		);  
	?>	

    <?= $form->field($model, 'estatus')->dropDownList(
            ['ACTIVO' => 'ACTIVO', 'INACTIVO' => 'INACTIVO']
		);   ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

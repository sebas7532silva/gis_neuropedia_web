<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Codigodescuentousuario */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="codigodescuentousuario-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'usuario')->textInput(['maxlength' => true])->label("Email") ?>

    <?= $form->field($model, 'tipo')->dropDownList(["PRUEBA 2 SEMANAS GRATIS"=>"PRUEBA 2 SEMANAS GRATIS","DESCUENTO"=>"DESCUENTO"])->label("Tipo") ?>

    <?= $form->field($model, 'porcentaje')->textInput()->label("Porcentaje (Solo aplica para 'Tipo: Descuento')") ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Codigodescuento */
/* @var $form yii\widgets\ActiveForm */
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script>

	$( document ).ready(function() {
		
		$( "#codigodescuento-fechainicio" ).datepicker({ dateFormat: 'yy-mm-dd',
		changeYear: true,});

		$( "#codigodescuento-fechafin" ).datepicker({ dateFormat: 'yy-mm-dd',
		changeYear: true,});
				
	});
</script>
<div class="codigodescuento-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'codigodescuento')->textInput(['maxlength' => true])->label("Código de Descuento") ?>

    <?= $form->field($model, 'porcentaje')->textInput()->label("Porcentaje de Descuento") ?>

    <?= $form->field($model, 'fechainicio')->textInput(["readonly"=>"true"])->label("Fecha Inicio") ?>

    <?= $form->field($model, 'fechafin')->textInput(["readonly"=>"true"])->label("Fecha Fin") ?>

    <?= $form->field($model, 'estatus')->dropDownList(["ACTIVO"=>"ACTIVO","INACTIVO"=>"INACTIVO"])->label("Estatus") ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

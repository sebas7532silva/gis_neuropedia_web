<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Usuario */
/* @var $form yii\widgets\ActiveForm */
?>

<div  style="font-size:1em; margin-top:0em; margin-bottom:1em; color:black">
	<h3>El Test con el cual conocerás si las habilidades del neurodesarrollo de tu peque van acorde a su edad.</h3>

	<div style="background-color:#5c9cfc; padding: 1em 1em 1em 1em; width:100%; text-align:center; border-radius:1em; margin: 2em 0 2em 0; width:90%; margin-left:5%">
		<p style="font-weight: 600; color:white; width:100%; text-align:center; font-size:2em">¿Qué lograrás?</p>
		<p style="margin-top:2em; margin-bottom:1em; color: white">Saber si las habilidades del neurodesarrollo de tus hijos/as están acorde a la edad o si presenta algún desfase y si así fuera, tener actividades para ayudarlo o si es necesario acudir a consulta.</p>
	</div>

<div style="width:100%; text-align:center">
	<video width="500" height="auto" controls>
	  <source src="../web/images/nbfv2.mp4" type="video/mp4">
	</video>
</div>
<div style="width:100%; text-align:center; margin-top:2em; margin-bottom: 2em">
	<div class="btn btn-primary" onclick='$("#usuario-email")[0].scrollIntoView();' style="width:10em; font-size: 2em; background-color: #5c9cfc; border:none">
		¡Comenzar!
	</div>
</div>

<p style="font-weight: 600; color:#5c9cfc; margin-top:2em; width:100%; text-align:center; font-size:2em">Beneficios</p>
<ul class="beneficios">
<li>Descubre si el desarrollo de tu bebé va de acuerdo a su edad</li>
<li>Abarca desde los 2 meses hasta los 5 años cumplidos</li>
<li>Conoce qué actividades necesita tu bebé para mejorar su desarrollo y alcanzar su máximo potencial</li>
<li>Recibe 10% de descuento en caso de requerir consulta médica</li>
<li>Obtén posibilidad de contemplar hasta un hijo(a) extra</li>
</ul>

<h3 style="margin-top:2em">Con sólo $499 MXN podrás evaluar y mejorar el desarrollo de 2 niños desde los 2 meses hasta sus 5 años cumplidos!</h3>

</div>

<div class="usuario-form" style="background-color:#d7c4d8; padding-top:2em; border-radius:1em; padding-left:2em; padding-right:2em; padding-bottom:1em">

    <?php $form = ActiveForm::begin([
            'options' => ['enctype' => 'multipart/form-data'],
            'fieldConfig' => ['errorOptions' => ['encode' => false, 'class' => 'help-block']] 
   ]); ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true])->label("Nombre y Apellido") ?>
	
	<?php
		$codigo="";
		if(isset($_GET["codigo"])){
			$codigo=$_GET["codigo"];
		}
	?>
	
    <?= $form->field($model, 'codigodescuento')->textInput(['maxlength' => true, 'value' =>$codigo])->label("Tengo un Código de Descuento") ?>	
	
	<a style="color:black;text-decoration: none;" href="web/index.php?r=usuario/terms" target="_blank">Al dar click en Registrar y Continuar en automático se aceptan los <span style="color:blue;text-decoration:underline">Términos y Condiciones</span></a>
	
    <div class="form-group" style="margin-top:1em">
        <?= Html::submitButton('Registrarme y Continuar', ['class' => 'btn btn-success', 'id'=>'btnregistrarme']) ?>
		<a class="btn btn-link" style="color:blue;font-weight:600" href="index.php?r=usuario/login">Ya Estoy Registrado (Login)</a>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<script>
	$("#btnregistrarme").click(function(){fbq('track', 'InitiateCheckout')});
</script>
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Preguntausuariorespuesta */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="preguntausuariorespuesta-form" >
	
	<div style="width:100%;  padding-top:2em">
		<p style="width: 100%; font-size: 2em; text-align: center"><?= $ep["pregunta"]?><?= ($epc["respuesta"]!=null)? "&nbsp;&nbsp;&nbsp;<span class='response'>".$epc["respuesta"]."</span></p>":"";?>
	<div>
	
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pregunta_id')->label(false)->hiddenInput(["value"=>$ep["pregunta_id"]]) ?>

    <?= $form->field($model, 'usuario_id')->label(false)->hiddenInput(['maxlength' => true, "value"=>$_SESSION["usuario"]]) ?>

    <?= $form->field($model, 'respuesta')->label(false)->hiddenInput(['maxlength' => true]) ?>
	
	<br/><br/>

    <div >
		<div style="width: 35%; float: left;">
			<a href="#" class="buttonyes" onclick="responder('SÍ');">Sí, lo hace</a>
		</div>
		<div style="width: 31%; float: left;  text-align: center">
		<a href="#" class="buttonsom" onclick="responder('A VECES');">A veces</a>
		</div>
		<div style="width: 33%; float: left; text-align:right">
		<a href="#" class="buttonnot" onclick="responder('NO');">Aún no</a>		
		</div>
    </div>
	
    <?php ActiveForm::end(); ?>

</div>

<script>
	function responder(resp){
		$("#preguntausuariorespuesta-respuesta").val(resp);
		$("#w0").submit();
	}
</script>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Cambiar Password';
?>
<div class="usuario-form">

    <?php $form = ActiveForm::begin(["id"=>"subform"]); ?>
	
	<div id="msgCambiado">
		<div class="alert alert-success" role="alert">
			¡Password cambiado con éxito!
		</div>
	</div>
	
	<div id="msgError">
		<div class="alert alert-danger" role="alert">
			Error, ambos campos son requeridos y deben coincidir.
		</div>
	</div>

    <div class="form-group required">
		<label class="control-label" for="pass-repeat">Nuevo Password</label>
		<input type="password" id="pass" class="form-control" name="password"  maxlength="100" aria-required="true" aria-invalid="false">
		<div class="help-block"></div>
	</div>
	
	<div class="form-group required">
		<label class="control-label" for="pass-repeat">Repetir Nuevo Password</label>
		<input type="password" id="pass-repeat" class="form-control"  maxlength="100" aria-required="true" aria-invalid="false">
		<div class="help-block"></div>
	</div>

    <div class="form-group">
        <button id="cambiar" class="btn btn-success"  >Cambiar</button>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
	$("#msgCambiado").hide();
	$("#msgError").hide();
	if(<?=$correcto?>){
		$("#msgCambiado").show();
	}
	$("#cambiar").on("click",function(){
		$("#msgCambiado").hide();
		event.stopPropagation();

		if($("#pass").val()==""){
			$("#msgError").show();
			return false;
		}
		
		if($("#pass").val()==$("#pass-repeat").val()){
			$("#subform").submit();
		}else{
			$("#msgError").show();
			return false;
		}
	});

</script>
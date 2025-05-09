<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Cliente */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cliente-form">
	
	<div id="msgError">
		<div class="alert alert-danger" role="alert">
			Estatus obligatorio.
		</div>
	</div>
	
    <?php $form = ActiveForm::begin(["id"=>"subform"]); ?>
	
	<hr></hr>
	<h3>Cliente</h3>
	<hr></hr>
	
    <?= $form->field($model, 'nombre')->textInput(['id'=>'nombre','maxlength' => true,'readonly'=>(!$modif)]) ?>

    <?= $form->field($model, 'prim_ap')->textInput(['maxlength' => true,'readonly'=>(!$modif)]) ?>

    <?= $form->field($model, 'seg_ap')->textInput(['maxlength' => true,'readonly'=>(!$modif)]) ?>
	
	<hr></hr>
	<h3>Conyuge</h3>
	<hr></hr>

    <?= $form->field($model, 'cony_nombre')->textInput(['maxlength' => true,'readonly'=>(!$modif)]) ?>

    <?= $form->field($model, 'cony_prim_ap')->textInput(['maxlength' => true,'readonly'=>(!$modif)]) ?>

    <?= $form->field($model, 'cony_seg_ap')->textInput(['maxlength' => true,'readonly'=>(!$modif)]) ?>
	
	<hr></hr>
	<h3>Desarrollo</h3>
	<hr></hr>

    <?= $form->field($model, 'num_int')->textInput(['maxlength' => true,'readonly'=>(!$modif)]) ?>

    <?= $form->field($model, 'vcv')->textInput(['maxlength' => true,'readonly'=>(!$modif)]) ?>

    <?= $form->field($model, 'monto_credito')->textInput(['maxlength' => true,'readonly'=>(!$modif)]) ?>
	
	<hr></hr>
	<h3>Equipo</h3>	
	<hr></hr>

    <?= $form->field($model, 'asesor')->dropDownList(
        $ail,
		['readonly'=>(!$modif)]
        ); ?>

    <?= $form->field($model, 'gerente')->dropDownList(
        $gil,
		['readonly'=>(!$modif)]
        ); ?>

        <?= $form->field($model, 'director_hipotecario')->dropDownList(
        $dil,
		['readonly'=>(!$modif)]
        ); ?>


	<hr></hr>
	<h3>Etapa y Estatus</h3>	
	<hr></hr>
	
	<div class="form-group field-etapa">
		<label class="control-label" for="etapa">Etapa</label>
		<?= Html::dropDownList("etapa","",$ecl,['prompt' => '--- Seleccionar ---','class'=>'form-control','id'=>'etapa','readonly'=>(!$modif)]) ?>
	</div>	
	

    <?= $form->field($model, 'estatus_cliente_id')->dropDownList([],['id'=>'estatus_cliente_id','readonly'=>(!$modif)]) ?>

	<div id="otro_estatus">
		<?= $form->field($model, 'otro_estatus')->textInput(['maxlength' => true,'readonly'=>(!$modif)]) ?>
	</div>
	
	<hr></hr>
	<h3>Comentarios</h3>	
	<hr></hr>

    <?= $form->field($model, 'comentarios')->textarea(['rows' => 6]) ?>	

    <div class="form-group">
        <button id="guardar" class="btn btn-success"  >Guardar</button>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
	$("#msgError").hide();

	$("#guardar").on("click",function(){
		$("#guardar").prop("disabled",true);
		event.stopPropagation();
		if($("#estatus_cliente_id").val()==null){
			$("#guardar").prop("disabled",false);
			$("#msgError").show();
			return false;
		}
		
		$("#subform").submit();

	});
	
	$("#subform").on("afterValidate", function (event, messages) {
		if($("#subform").find('.has-error').length>0) {
			$("#guardar").prop("disabled",false);
		}
	});


	function first(obj) {
		for (var a in obj) return a;
	}
	
	$("#etapa").on("change",function(){
		var v = $("#etapa").val();
		var etapas = {
			"ETAPA 1. PRIMERA PARTE": [{1:"CLIENTE ATENDIDO"},{2:"CLIENTE VIENDO OPCIONES"},{3:"CLIENTE REUNIENDO DOCUMENTOS"},{4:"CLIENTE NO VIABLE"},{5:"CLIENTE NO INTERESADO"},{6:"CLIENTE YA TIENE CREDITO"},{7:"NO CONTESTA"},{8:"OTRO"}], 
			"ETAPA 1. SEGUNDA PARTE": [{9:"AUTORIZADO POR MONTO SOLICITADO"},{10:"AUTORIZADO POR MONTO MENOR AL SOLICITADO"},{11:"AUTORIZADO CONDICIONADO"},{12:"RECHAZADO"},{13:"CLIENTE EN RIESGOS"},{14:"CLIENTE EN BURÓ"},{15:"DOCUMENTOS ADICIONALES"},{16:"EN ANALISIS"},{17:"CLIENTE CANCELADO"},{18:"OTRO"}], 
			"ETAPA 2": [{26:"AVALUO"},{27:"AVALUO PENDIENTE"},{28:"AVALUO CONCLUIDO"},{29:"INMUEBLE NO VIABLE"},{30:"OTRO"}], 
			"ETAPA 3": [{19:"EVALUACIÓN DE JURIDICO"},{20:"FECHA DE FIRMA PENDIENTE"},{21:"PROGRAMAR FECHA DE FIRMA"},{22:"FECHA DE FIRMA"},{23:"OTRO"}], 
			"ETAPA 4": [{24:"CLIENTE FIRMADO"},{25:"OTRO"}]
		};
		var s = "";
		$.each( etapas[v], function( key, value ) {
			s += '<option value="'+first(value)+'">'+value[first(value)]+'</option>' ;
		});
		$("#estatus_cliente_id").html(s);
	});
		
	$("#estatus_cliente_id").on("change",function(){
		var v = $("#estatus_cliente_id option:selected").text();
		if(v=="OTRO"){
			$("#otro_estatus").show();
		}else{
			$("#otro_estatus").hide();
			$("#otro_estatus").val("");
		}
	});
	
	$("#otro_estatus").hide();
	var v = $("#estatus_cliente_id").text();
	if(v=="OTRO"){
		$("#otro_estatus").show();
	}	
</script>

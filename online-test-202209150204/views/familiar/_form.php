<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Familiar */
/* @var $form yii\widgets\ActiveForm */

$color="grey";
if($model["color"]!=null){
	$color=$model["color"];
}

?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script>

	$( document ).ready(function() {
		thisyear = new Date().getFullYear();
		firstyear = thisyear-5;
		yrange=firstyear+":"+thisyear;
		
		$( "#familiar-color" ).change(function(){
			$("#lblcambioinfo").css("color",$("#familiar-color").val());
			$("#butback").css("color",$("#familiar-color").val());
			$("#butback").css("border-color",$("#familiar-color").val());
		});
		
		$( "#familiar-fechanacimiento" ).datepicker({ dateFormat: 'yy-mm-dd',
		changeYear: true,
		yearRange: yrange});
		
		$("#premyesno").change(function(){
			if($("#premyesno").val()=="SI"){
				$("#premform").show();
			}else{
				$("#premform").hide();
			}
		});

		$("#premtime").change(function(){
			if($("#premunit").val()=="SEMANAS"){
				$("#familiar-semanasprematuro").val($("#premtime").val());
			}else{
				$("#familiar-semanasprematuro").val($("#premtime").val()*4);
			}
		});

		$("#premunit").change(function(){
			if($("#premunit").val()=="SEMANAS"){
				$("#familiar-semanasprematuro").val($("#premtime").val());
			}else{
				$("#familiar-semanasprematuro").val($("#premtime").val()*4);
			}
		});

		
		
	});
</script>
<style>
	h1{
		display:none;
	}
</style>

<div class="familiar-form">

    <?php $form = ActiveForm::begin(); ?>
	
	<?php if($model->nombre){	?>
		<h2><?=$model->nombre?><span id="lblcambioinfo" style="color:<?=$color?>"> :: Cambiar Información</span></h2>
	<?php }else{	?>
		<h2><span id="lblcambioinfo" style="color:<?=$color?>">Nuevo Integrante de mi Familia</span></h2>
	<?php }	?>
	<br/>
    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true])->label('Nombres',['class'=>'label-class']) ?>

    <?= $form->field($model, 'apellido')->textInput(['maxlength' => true])->label('Primer Apellido',['class'=>'label-class']) ?>

    <?= $form->field($model, 'apellido2')->textInput(['maxlength' => true])->label('Segundo Apellido',['class'=>'label-class']) ?>

	<?php 	if($modo=="crear"){ 
				echo $form->field($model, 'fechanacimiento')->textInput(['style' => "width: 10em", "readonly"=>"true"])->label('Fecha de Nacimiento',['class'=>'label-class']) ;
				echo '<label class="label-class" for="familiar-semanasprematuro">¿Tu bebé fue prematuro?</label><br/><br/>';
				echo "<select id='premyesno' class='form-control'><option value='NO'>No</option><option value='SI'>Sí</option></select></select><br/><br/>";
				echo "<div id='premform' style='display:none'>";
				echo '<label class="label-class" for="tiempo">¿De cuantas semanas nació (las semanas normales son 40)? </label><br/><br/>';
				echo "<input id='premtime' type='text' class='form-control' value='0'>&nbsp;<select id='premunit' class='form-control'><option value='SEMANAS'>Semanas</option><option value='MESES'>Meses</option></select></select><br/><br/>";
				echo $form->field($model, 'semanasprematuro')->hiddenInput(['style' => "width: 5em", 'value'=>"0"])->label(false);				
				echo "</div>";
				
			}else{
				echo '<label class="label-class" for="familiar-fechanacimiento">Fecha de Nacimiento</label><br/>';
				echo $model->fechanacimiento;
				echo '<br/><br/>';
				
				if($model->semanasprematuro>0){
					echo '<label class="label-class" for="familiar-semanasprematuro">Mi bebé nació de esta cantidad de semanas</label><br/>';
					echo $model->semanasprematuro." semanas";
					echo '<br/><br/>';
				}else{
					echo '<label class="label-class" for="familiar-semanasprematuro">¿Tu bebé fue prematuro?</label><br/>';
					echo 'No';
					echo '<br/><br/>';					
				}
				
			}
	?>

	
	<?= $form->field($model, 'color')->dropDownList(["#0066cc"=>"Azul", "cadetblue" => "Azul Grisáceo", "pink"=>"Rosa",  "deeppink"=>"Rosa Brillante",  "blueviolet"=>"Morado", "darkmagenta" => "Morado Obscuro", "darkkhaki" => "Amarillo Obscuro",  "darkgoldenrod" => "Dorado Obscuro",  "brown" => "Café", "chocolate" => "Chocolate",  "crimson" => "Carmesí",  ])->label('Color favorito para tu bebé',['class'=>'label-class']) ?>

    <?= $form->field($model, 'genero')->dropDownList(["Niño"=>"Niño","Niña"=>"Niña"])->label('Género',['class'=>'label-class']) ?>

    <?= $form->field($model, 'parentesco')->dropDownList(["Padre"=>"Padre","Madre"=>"Madre","Tutor"=>"Tutor","Maestro/a"=>"Maestro/a","Otro/a"=>"Otro/a"])->label('Parentesco',['class'=>'label-class']) ?>

    <div style="margin-top:2em">
		<div style="float: left; margin-right: 2em">
			<?= Html::submitButton('Guardar', ['class' => 'btn btn-success', "style"=>"border-radius: 2em; padding: 1em 2em 1em 2em"]) ?>
		</div>
		<div style="width: 60%; padding-left: 8em; float: left;">
			<a href="index.php?r=familiar/index">
				<div id="butback" style="padding-left: 2em;border: 1px solid <?=$color?>;width: 10em;float: left;text-align: center;border-radius: 2em;padding-right: 2em;padding-top: 1em;padding-bottom: 1em;color:<?=$color?>; font-weight:600">
					Regresar
				</div>
			</a>
		</div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

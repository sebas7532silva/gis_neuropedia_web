<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\Preguntausuariorespuesta */

$this->title = $ep["examen"]["titulo"];
$this->params['breadcrumbs'][] = ['label' => 'Preguntausuariorespuestas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$color=$familiar["color"];
			
$keyans=(sizeof($keyans)>0)?$keyans:[];
$text="";

$i=0;
foreach ($eps as $key => $value) {
	$i++;
	if($value["competencia_id"]==$competencia["competencia_id"]){
		
		if($value["pregunta_id"]==$ep["pregunta_id"]){
			$text.= '<div class="stepper-item active">
			
			'.Html::a('<div class="step-counter">'.$i.'</div>', ['preguntausuariorespuesta/create', 'busc' => $value["pregunta_id"],'familiarid'=>$familiarid],  ['class' => 'step-link']).'

			</div>';				
			
		}elseif(in_array($value["pregunta_id"],$keyans)){
			$text.= '<div class="stepper-item completed">
			
			'.Html::a('<div class="step-counter">'.$i.'</div>', ['preguntausuariorespuesta/create', 'busc' => $value["pregunta_id"],'familiarid'=>$familiarid],  ['class' => 'step-link']).'

			</div>';
		}else{
			$text.= '<div class="stepper-item"><div class="step-counter">
			'.Html::a('<div class="step-counter">'.$i.'</div>', ['preguntausuariorespuesta/create','familiarid'=>$familiarid],  ['class' => 'step-link']).'
			</div></div>';
		}
	}else{
		$i=0;
	}

}

?>
<style>
.stepper-item.active .step-counter {
  color: <?=$color?>;
  border: 2px solid <?=$color?>;
}


.stepper-item.active::after {
  border-bottom: 2px solid <?=$color?>;
}


.stepper-item.completed .step-counter {
  background-color: <?=$color?>;
}

.stepper-item.completed::after {
  border-bottom: 2px solid <?=$color?>;
}


</style>

<div class="preguntausuariorespuesta-create">

<div class="showdesk" style="text-align: center">

    <h2><?=$familiar["nombre"]?> :: <?="<span class='lblcompetencia' style='color:".$color."'>".$competencia["competencia"]."</span>"?> <span style="color: grey">-&nbsp;<?=$ep["edad"]["edad"]?></span></h2>
	
	<br/><br/>

	<div class="stepper-wrapper">
		<?=$text?>
	</div>	
</div>	

<div class="preguntausuariorespuesta-form" >
	<div class="showmobile" style="padding-top:2em; text-align: center; width:100%">

		<div class="stepper-wrapper" style="float:left; width:100%; padding-bottom: 2em">
			<?=$text?>
		</div>	
				
	</div>

	<div style="width:100%;" class="questionpadding">
		<p style="width: 100%; text-align: center" class="questionparagraphfont"><?= $ep["pregunta"]?><?= ($epc["respuesta"]!=null)? "&nbsp;&nbsp;&nbsp;<span class='response'>".$epc["respuesta"]."</span></p>":"";?>
		<?php if($ep["imagen"]!=null){ ?>
		<div style="width:100%;text-align: center; margin-top:2em">
			<img src="../web/images/questions/<?=$ep["imagen"]?>"/>
		</div>
		<?php } ?>
	<div>

	<div style="width:100%;  padding-top:0em; padding-bottom:0.5em;" class="showmobile">
		<table style="width:100%; border:0px; font-size: 1.5em">
			<tr>
				<?php if($competencia["competencia_id"]==1){ ?>
			
					<td style="width:100%; text-align: center; background-color: #0cf; color: white; font-weight:bold; padding-top: 1em; padding-bottom:1em; border: 10px solid white"><a href="#" onclick="responder('SÍ');">CORRECTO, ASÍ LO HARÉ</a></td>
			
				<?php }else{ ?>
			
					<td style="width:33%; text-align: center; background-color: #0cf; color: white; font-weight:bold; padding-top: 1em; padding-bottom:1em; border: 10px solid white"><a href="#" onclick="responder('SÍ');">SÍ</a></td>
					<td style="width:33%; text-align: center; background-color: #049cdc; color: white; font-weight:bold; 									border: 10px solid white"><a href="#" onclick="responder('A VECES');">A VECES</a></td>
					<td style="width:33%; text-align: center; background-color: #ddd; color: black; font-weight:bold;  										border: 10px solid white"><a href="#" onclick="responder('NO');">AÚN NO</a></td>

				<?php } ?>					
				
			</tr>
		</table>
	</div>

	<div class="showdesk">

		<div id="laforma" style="width:100%;height: auto;float: left;padding-bottom: 3em;">
	
		<?php $form = ActiveForm::begin(); ?>

		<?= $form->field($model, 'pregunta_id')->label(false)->hiddenInput(["value"=>$ep["pregunta_id"]]) ?>

		<?= $form->field($model, 'usuario_id')->label(false)->hiddenInput(['maxlength' => true, "value"=>$_SESSION["usuario"]]) ?>

		<?= $form->field($model, 'respuesta')->label(false)->hiddenInput(['maxlength' => true]) ?>
		
		<br/><br/>

		<?php if($competencia["competencia_id"]==1){ ?>

			<div style="width: 100%; text-align: center;">
				<a href="#" class="buttonyes" onclick="responder('SÍ');">Correcto, así lo haré</a>
			</div>						
			<div style="width: 100%; text-align: center;">			
				<img src="../web/images/fireman.png"/>
			</div>		
		
		<?php }else{ ?>

			<div style="width: 35%; float: left;">
				<a href="#" class="buttonyes" onclick="responder('SÍ');">Sí, lo hace</a>
			</div>
			<div style="width: 31%; float: left;  text-align: center">
				<a href="#" class="buttonsom" onclick="responder('A VECES');">A veces</a>
			</div>
			<div style="width: 33%; float: left; text-align:right">
				<a href="#" class="buttonnot" onclick="responder('NO');">Aún no</a>		
			</div>
		
		<?php } ?>	
			
		</div>
    </div>
	
    <?php ActiveForm::end(); ?>

</div>

	<div class="showmobile" style="padding-top:2em; text-align: center; width:100%">

		<div style="float:left;width:100%">
		<h2><?=$familiar["nombre"]?> :: <?="<span class='lblcompetencia' style='color:".$color."'>".$competencia["competencia"]."</span>"?><span style="color: grey">&nbsp;-&nbsp;<?=$ep["edad"]["edad"]?></span></h2>
		</div>
		
	</div>


<div id="contenidoSelect" style="display:none;">
	<select id="cambioCompetencia" style="padding-left:0.5em;padding-right:0.5em; border-radius:1em">
		
		<?php
			foreach($competencias as $compet){ 
				$sele=($compet["competencia_id"]==$competencia["competencia_id"])?"selected":"";
		?>
			<option <?=$sele?> value="<?=$compet["pregunta_id"]?>"><?=$compet["competencia"]?></option>		
		<?php }?>
	</select>
</div>


<script>
	function responder(resp){
		$("#preguntausuariorespuesta-respuesta").val(resp);
		$("#w0").submit();
	}
	
	$(".lblcompetencia").click(function(){
		
		$(".lblcompetencia").html($("#contenidoSelect").html());
		$(".lblcompetencia").removeClass("lblcompetencia");

	});	
	
	$('.lblcompetencia').on('change', '#cambioCompetencia', function(){
		var pregunta= $(this).val();
		var familiarid= <?=$familiarid?>;
		window.location = '../web/index.php?r=preguntausuariorespuesta%2Fcreate&busc='+pregunta+'&familiarid='+familiarid;
	});		
</script>

	

</div>


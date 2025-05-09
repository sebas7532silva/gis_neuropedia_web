<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Actividadfamiliarhistorico */
/* @var $form yii\widgets\ActiveForm */

$color=$familiar["color"];

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
		
		$( "#actividadfamiliarhistorico-fecha" ).datepicker({ dateFormat: 'yy-mm-dd',
		changeYear: true,
		yearRange: yrange});
		$( "#actividadfamiliarhistorico-fecha" ).datepicker("setDate",'now');
	});
</script>



<div class="actividadfamiliarhistorico-form" style="margin-top:2em">




    <?php $form = ActiveForm::begin(); ?>

	<?php 
		
		$copyhistorico="¡Es la primera vez que realizas esta actividad!";
		$copyvez=$ahsn>1?"veces":"vez";
		$copyen=$ahsn>1?"y la última ocasión fue":"";
		if($ahsn>0){
			$copyhistorico="Has realizado la actividad ".$ahsn." ".$copyvez." ".$copyen." en: ".$ahs["fecha"].".";
	?>
	<a href="index.php?r=actividadfamiliarhistorico/index&familiarid=<?=$familiar["familiar_id"]?>&actividadid=<?=$actividad["actividad_id"]?>">	
		<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-calendar-check" viewBox="0 0 16 16" style="color:<?=$color?>;vertical-align: bottom"><path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/><path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/></svg>
	</a>
	
	<span style="padding-left: 1em; color: grey; "><?=$copyhistorico?></span>

	<div style="padding-top:2em"></div>	
	<?php
			
		}
	?>
	
    <?= $form->field($model, 'fecha')->textInput(['style' => "width: 10em", "readonly"=>"true"]) ?>

	
	<label class="control-label">Descripción de la Actividad:</label>	
	<p>
		<?=$actividad["actividad"]?>
	</p>

    <?= $form->field($model, 'notas')->textarea(['rows' => 6]) ?>

    <div style="float:left; margin-right: 2em">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success', "style"=>"border-radius: 2em; padding: 1em 2em 1em 2em"]) ?>
    </div>
		<div style="width: 60%; padding-left: 8em; float: left;">
			<a href="index.php?r=actividad/index&familiarid=<?=$familiarid?>">
				<div style="padding-left: 2em;border: 1px solid <?=$color?>;width: 10em;float: left;text-align: center;border-radius: 2em;padding-right: 2em;padding-top: 1em;padding-bottom: 1em;color:<?=$color?>; font-weight:600">
					Regresar
				</div>
			</a>
		</div>
	

    <?php ActiveForm::end(); ?>


</div>

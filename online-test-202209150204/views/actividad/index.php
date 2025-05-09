<?php

use yii\helpers\Html;
use yii\grid\GridView;
require_once "../models/Funciones.php";


/* @var $this yii\web\View */
/* @var $searchModel app\models\ActividadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Actividades Sugeridas';
$this->params['breadcrumbs'][] = $this->title;
$color=$familiar["color"];

?>

<script>
	function randomchoose(){
		var r= Math.floor(Math.random() * <?=sizeof($actividades)?>);
		 $('html, body').animate({
			scrollTop: $("#num-"+r).offset().top-50
		}, 2000, function(){
			$("#num-"+r).fadeTo(200, 0.3, function() { $(this).fadeTo(500, 1.0); });
		});

	}
</script>
<div class="actividad-index" style="padding-right: 3em">

	<div style="width:100%; text-align:center; padding-left: 3em">
		<h1><?=$familiar["nombre"]?> :: <span style='color:<?=$color?>'>Actividades Sugeridas</span> <span style="color:grey"> - <?=calcularEdadTexto($familiar["fechanacimiento"])?>&nbsp;&nbsp;</span>
			
			<a href="#" onclick="randomchoose()";>
				<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" style="color:<?=$color?>" class="bi bi-dice-4-fill" viewBox="0 0 16 16"><path d="M3 0a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V3a3 3 0 0 0-3-3H3zm1 5.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm8 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm1.5 6.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zM4 13.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/></svg>
			</a>
			
		</h1>
	</div>

	<?php 
		$i=0;
		foreach($actividades as $actividad){
			$i++;

	?>
		<div id="num-<?=$i?>" style="border: 2px solid <?=$color?>; margin: 1em; float: left; color: black; padding-top: 1em; border-radius: 1.1em; font-size: 1.5em" class="activitiescardwidth">
			<div style="width:100%;color: <?=$color?>;text-align:center; font-size:2em;font-weight:bold">
			<?=$i?>
			</div>
			<div style="padding:1em; ">
				<?=$actividad["actividad"]?>
			</div>

			<div style="margin-top:1em; padding-right:1em; padding-left:1em; padding-bottom:0.5em; padding-top:0.5em; background-color:<?=$color?>;border-radius: 0em 0em 1em 1em;">
				<a href="index.php?r=actividadfamiliarhistorico/create&familiarid=<?=$familiar["familiar_id"]?>&actividadid=<?=$actividad["actividad_id"]?>">
					<img src="../web/images/suit-heart-fill.svg" style="width:1.5em;vertical-align: sub;">
				</a>
				<?php if($actividad["nh"]>0){ ?>
				<a href="index.php?r=actividadfamiliarhistorico/index&familiarid=<?=$familiar["familiar_id"]?>&actividadid=<?=$actividad["actividad_id"]?>">					
					<img src="../web/images/calendar-check.svg" style="width:1.5em;vertical-align: sub; float:right;">
				</a>
				<?php } ?>				
			</div>			
		</div>
		
		<div style="margin-top:2em; width=100%; text-align: center; padding-left:2em">
			<a href="index.php?r=familiar/index">
				<div style="padding-left: 2em;background-color:<?=$color?>;width: 10em;float: left;text-align: center;border-radius: 2em;padding-right: 2em;padding-top: 1em;padding-bottom: 1em;color:white">
					Regresar
				</div>
			</a>
		</div>
		
	<?php
		}
	?>

</div>

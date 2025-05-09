<?php

use yii\helpers\Html;
use yii\grid\GridView;
require_once "../models/Funciones.php";
require_once  "../stripepay/config.php";

/* @var $this yii\web\View */
/* @var $searchModel app\models\FamiliarSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = 'Mi Familia';
$this->params['breadcrumbs'][] = $this->title;
?>

<script charset="utf-8" src="https://js.stripe.com/v3/fingerprinted/js/co-intl-locale-bundle-es-419-3abe69ff45335c671ea8cfd478dd5635.js"></script>
<div class="familiar-index">
	<?php

		if($_SESSION["horasdisponibles"]==0&&!$freetrial){
			echo "<h2>¡Felicidades! Ya diste de alta tu cuenta en Neurobaby</h2>";
			echo "<hr>";
			echo "<p style='font-size: 1.5em'>Para poder comenzar la evaluación, es necesario pagar los exámenes.</p>";
			if($descuento>0){
				echo "<p style='font-size: 1.5em'>El precio con tu <span style='color:blue'> ".$descuento."% de descuento </span> es de $ ".(($stripeDetails["precioLoginNeurobaby"]/100)-($stripeDetails["precioLoginNeurobaby"]/100)*($descuento/100))." MXN e incluye a 2 niños desde sus 2 	meses hasta sus 5 años.</p>";
			}else{
				echo "<p style='font-size: 1.5em'>El precio por introducción es de $ ".(($stripeDetails["precioLoginNeurobaby"]/100)-($stripeDetails["precioLoginNeurobaby"]/100)*($descuento/100))." MXN e incluye a 2 niños desde sus 2 	meses hasta sus 5 años.</p>";
			}
			echo "<hr>";
	?>
		<div class="row">
			<div class="col-md-12">
				<div class="form-container">
					<form autocomplete="off" action="index.php?r=pago%2Fcreate" 	method="POST">			   				
						<input type="hidden" value="nblogin" name="producto">
						<script
						src="https://checkout.stripe.com/checkout.js" class="stripe-button"
						data-key="<?=$stripeDetails["publishableKey"]?>"
						data-amount="<?=($stripeDetails["precioLoginNeurobaby"]-$stripeDetails["precioLoginNeurobaby"]*($descuento/100))?>"
						data-name="Neurobaby"
						data-description="Registro 2 niños a Neurobaby."
						data-image="../web/images/fireman.png"
						data-currency="mxn"
						data-locale="mx">
						</script>
					</form>
				</div>
			</div>
		</div> 
	
	<?php
		}else{
			if(isset($_SESSION["pagado"])&&$_SESSION["pagado"]=="YES"){
				$_SESSION["pagado"]="NO";
				echo "<script>fbq('track', 'Purchase', {value: ".($_SESSION["pagadoprecio"]/100).", currency: 'MXN'});</script>";
				$_SESSION["pagadoprecio"]=0;
			}
			
	?>
		
	<div style="width:100%">
	<?php 
	
		foreach($familia as $miembro){
			$color=$miembro["color"];
			
	?>
		<div style="background-color: <?=$color?>; margin: 1em; float: left; color: white; padding: 1em; border-radius: 1em" class="indexcardwidth">
			<div style="width:100%; text-align: center; " >
				<a href="index.php?r=preguntausuariorespuesta%2Fcreate&familiarid=<?=$miembro["familiar_id"]?>" title="Iniciar Examen" style="text-decoration:none;">
					<img src="../web/images/file-plus-fill.svg" style="width:20%">
				</a>
				<img src="../web/images/baby1.png" style="width:45%">
				<a href="index.php?r=actividad%2Findex&familiarid=<?=$miembro["familiar_id"]?>" title="Actividades Sugeridas" style="text-decoration:none;">
					<img src="../web/images/person-heart.svg" style="width:25%">
				</a>
			</div>
			<div style="margin-top:1em; margin-bottom:1em; background-color: white; color: black; padding: 1em; border-radius: 1em">
				<div style="margin-bottom:1em; font-weight: 800; font-size: 2em">
					<div style="margin-right:2em">
						<?=$miembro["nombre"]?>&nbsp;<span style="font-weight: 400; color: grey">(<?=calcularEdadTexto($miembro["fechanacimiento"], $miembro["semanasprematuro"])?>)</span>&nbsp;<a href="index.php?r=familiar/update&id=<?=$miembro["familiar_id"]?>" title="Cambiar Datos" style="text-decoration:none;"><img src="../web/images/pencil.svg" style="width:0.75em; vertical-align: baseline;"></a>
					</div>
				</div>			
				<div style="margin-top:1em; margin-bottom:1em;padding-left:1em">
					
					
					<?php
						$aviso=false;
						$skip="";
						
						if(cumpleAgnos($miembro["fechanacimiento"])){
							echo $skip.'<img src="../web/images/star-fill.svg" style="width:1.2em;vertical-align: text-bottom;">&nbsp;<span>¡Feliz cumpleaños '.$miembro["nombre"].'!<span>';
							$aviso=true;
							$skip="<br/><br/>";
						}else{
							if(cumpleMes($miembro["fechanacimiento"])){
								echo $skip.'<img src="../web/images/star-fill.svg" style="width:1.2em;vertical-align: text-bottom;">&nbsp;<span>¡Feliz cumple-mes '.$miembro["nombre"].'!<span>';
								$aviso=true;
								$skip="<br/><br/>";
							}											
						}
						
						if($miembro["enexamen"]){
							echo $skip.'<a href="index.php?r=preguntausuariorespuesta%2Fcreate&familiarid='.$miembro["familiar_id"].'"><img src="../web/images/bell-fill.svg" style="width:1.2em;vertical-align: text-bottom;">&nbsp;<span>Faltan algunas preguntas (examen en curso)<span></a>';
							$aviso=true;
							$skip="<br/><br/>";
						}else{						
							if($miembro["nuevoex"]){
								echo $skip.'<a href="index.php?r=preguntausuariorespuesta%2Fcreate&familiarid='.$miembro["familiar_id"].'"><img src="../web/images/hand-thumbs-up-fill.svg" style="width:1.2em;vertical-align: text-bottom;">&nbsp;<span>'.$miembro["nombre"].' tiene un nuevo examen disponible.<span></a>';
								$aviso=true;
								$skip="<br/><br/>";
							}
						}
												
						if($miembro["actividad"]=="nunca"){
							echo $skip.'<a href="index.php?r=actividad%2Findex&familiarid='.$miembro["familiar_id"].'"><img src="../web/images/person-hearts.svg" style="width:1.2em;vertical-align: text-bottom;">&nbsp;<span>¡Conoce las actividades disponibles! <span></a>';
							$aviso=true;
							$skip="<br/><br/>";
						}elseif(calcularDiferenciaConHoy($miembro["actividad"])>=3){
							echo $skip.'<a href="index.php?r=actividad%2Findex&familiarid='.$miembro["familiar_id"].'"><img src="../web/images/person-hearts.svg" style="width:1.2em;vertical-align: text-bottom;">&nbsp;<span>La última vez que hiciste una actividad con tu peque fue hace '.calcularDiferenciaConHoy($miembro["actividad"]).' días.<span></a>';
							$aviso=true;
							$skip="<br/><br/>";
						}
												
						if(!$aviso){
							if(calcularDiferenciaConHoy($miembro["fechaalta"])<3){
								echo $skip.'<img src="../web/images/heart-fill.svg" style="width:1.2em;vertical-align: text-bottom;">&nbsp;<span>¡Gracias por darme de alta!<span>';
							}else{
								echo $skip.'<img src="../web/images/check-circle-fill.svg" style="width:1.5em;vertical-align: sub;">&nbsp;<span>Sin avisos pendientes</span>';
							}
						}
					
					?>
				</div>
				<div style="margin-top:1em; margin-bottom:3em;padding-left:1em;">
					<?php if($miembro["famililarhistoricos"]){ ?>
					<a href="index.php?r=preguntausuariorespuesta%2Fexamenes&familiarid=<?=$miembro["familiar_id"]?>" title="Mis Exámenes Hechos" style="text-decoration:none;">
						<img src="../web/images/files.svg" style="width:5%; float:right"><span style="float:right;margin-right:1em; color:grey">Exámenes Anteriores</span>
					</a>
					<?php } ?>					
				</div>

			</div>
		</div>
	<?php 
			
		}
	?>
	
	<?php if(count($familia)<$_SESSION["horasdisponibles"]){ ?>
	<div style="border: 1em dashed grey; margin: 1em; float: left; color: white; padding: 1em; border-radius: 1em; text-align: center" class="indexcardwidth">
		<a href="index.php?r=familiar%2Fcreate">	
			<div style="width:100%; " >
				<img src="../web/images/person-plus-fill.svg" style="width:40%">			
			</div>
		</a>
	</div>	
	<?php } ?>

	</div>

	<?php
		}
		
	?>

</div>

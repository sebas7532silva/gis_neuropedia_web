<?php


use yii\helpers\Html;
use yii\grid\GridView;
use \yii\helpers\Url;
require_once  "../stripepay/config.php";


/* @var $this yii\web\View */
/* @var $searchModel app\models\UsuarioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Comprar Examen Extra";
$this->params['breadcrumbs'][] = $this->title;

?>

<script charset="utf-8" src="https://js.stripe.com/v3/fingerprinted/js/co-intl-locale-bundle-es-419-3abe69ff45335c671ea8cfd478dd5635.js"></script>

<div class="usuario-index">

	<?php
		if($freetrial){	
	?>
		<h2>Espera a Terminar tu Periodo de Prueba</h2>
		<hr>
		<p style='font-size: 1.5em'> Actualmente no puedes comprar más exámenes. Espera a finalizar tu periodo de prueba.</p>

	<?php
		}else{	
	?>


	<h2>Comprar Examen para Otro Bebé</h2>
	<hr>
	<p style='font-size: 1.5em'> Actualmente puedes registrar hasta <?=$_SESSION["horasdisponibles"]?> bebés para usar Neurobaby. Solo utiliza esta opción si deseas registrar más de <?=$_SESSION["horasdisponibles"]?> bebés.</p>
	<?php
		echo "<p style='font-size: 1.5em'>Registrar un bebé más tiene un costo de $ ".($stripeDetails["precioExtra1Neurobaby"]/100)." MXN. </p>";
	?>
			
		<hr>
		<div class="row">
			<div class="col-md-12">
				<div class="form-container">
					<form autocomplete="off" action="index.php?r=pago%2Fcreate" 	method="POST">
						<input type="hidden" value="nbextra1" name="producto">
						<script
						src="https://checkout.stripe.com/checkout.js" class="stripe-button"
						data-key="<?=$stripeDetails["publishableKey"]?>"
						data-amount="<?=$stripeDetails["precioExtra1Neurobaby"]?>"
						data-name="Neurobaby"
						data-description="Registrar 1 bebé extra a Neurobaby."
						data-image="../web/images/fireman.png"
						data-currency="mxn"
						data-locale="mx">
						</script>
					</form>
				</div>
			</div>
		</div>

	<?php
		}
	?>
		

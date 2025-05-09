<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ActividadfamiliarhistoricoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Actividadfamiliarhistoricos';
$this->params['breadcrumbs'][] = $this->title;

$color=$familiar["color"];


?>
<div class="actividadfamiliarhistorico-index" style="padding-right:3em">


	<?php 
		
		$copyhistorico="¡Es la primera vez que realizas esta actividad!";
		$copyvez=$ahsn>1?"ocasiones":"ocasión";
		$copyen=$ahsn>1?"y la última fue":"";
		if($ahsn>0){
			$copyhistorico="Has realizado la actividad número <span style='color: ".$color."'> ".$actividad["actividad_id"]."</span> con <span style='color: ".$color."'>".$familiar["nombre"]."</span> en  <span style='color: ".$color."'>".$ahsn."</span> ".$copyvez." ".$copyen." en: <span style='color: ".$color."'>".$historicos[0]["fecha"]."</span>.";
	?>
	
	<div style="padding-top:2em;width:100%; text-align: center; ">
	
		<span style="color: black; font-size: 1.5em"><?=$copyhistorico?></span>
		
	</div>
	
	<div style="padding-top:2em"></div>	
	<?php
			
		}
	?>

	<?php 
		foreach($historicos as $historico){
	?>
		<div style="width:100%;  border: 2px solid <?=$color?>; margin: 1em; float: left; color: black;  border-radius: 1.1em; font-size: 1.5em">
			<div style="width:100%; color: white;text-align:center; font-size:1em; background-color:<?=$color?>; padding-top:0.3em;padding-bottom:0.3em; border-radius: 1em 1em 0em 0em">
				<?=$historico["fecha"]?>
			</div>
			<div style="padding:1em; ">
				<?=$historico["notas"]?>
			</div>
		</div>
		
		<div style="margin-top:2em; width=100%; text-align: center; padding-left:2em">
			<a href="index.php?r=actividad/index&familiarid=<?=$familiarid?>">
				<div style="padding-left: 2em;background-color:<?=$color?>;width: 10em;float: left;text-align: center;border-radius: 2em;padding-right: 2em;padding-top: 1em;padding-bottom: 1em;color:white">
					Regresar
				</div>
			</a>
		</div>
		
	<?php
		}
	?>



</div>

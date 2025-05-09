<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Preguntausuariorespuesta */

$this->title = "Resultados";
$this->params['breadcrumbs'][] = ['label' => 'Preguntausuariorespuestas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
require_once "../models/Funciones.php";


$familiarid=$_GET["familiarid"];

		$star='<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="color: green" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16"><path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
		</svg>';
		$bicycle='<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="color: blue" fill="currentColor" class="bi bi-palette-fill" viewBox="0 0 16 16"><path d="M12.433 10.07C14.133 10.585 16 11.15 16 8a8 8 0 1 0-8 8c1.996 0 1.826-1.504 1.649-3.08-.124-1.101-.252-2.237.351-2.92.465-.527 1.42-.237 2.433.07zM8 5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm4.5 3a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zM5 6.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm.5 6.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/></svg>';		
		$bell='<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="color: orange" fill="currentColor" class="bi bi-signal" viewBox="0 0 16 16"><path d="m6.08.234.179.727a7.264 7.264 0 0 0-2.01.832l-.383-.643A7.9 7.9 0 0 1 6.079.234zm3.84 0L9.742.96a7.265 7.265 0 0 1 2.01.832l.388-.643A7.957 7.957 0 0 0 9.92.234zm-8.77 3.63a7.944 7.944 0 0 0-.916 2.215l.727.18a7.264 7.264 0 0 1 .832-2.01l-.643-.386zM.75 8a7.3 7.3 0 0 1 .081-1.086L.091 6.8a8 8 0 0 0 0 2.398l.74-.112A7.262 7.262 0 0 1 .75 8zm11.384 6.848-.384-.64a7.23 7.23 0 0 1-2.007.831l.18.728a7.965 7.965 0 0 0 2.211-.919zM15.251 8c0 .364-.028.727-.082 1.086l.74.112a7.966 7.966 0 0 0 0-2.398l-.74.114c.054.36.082.722.082 1.086zm.516 1.918-.728-.18a7.252 7.252 0 0 1-.832 2.012l.643.387a7.933 7.933 0 0 0 .917-2.219zm-6.68 5.25c-.72.11-1.453.11-2.173 0l-.112.742a7.99 7.99 0 0 0 2.396 0l-.112-.741zm4.75-2.868a7.229 7.229 0 0 1-1.537 1.534l.446.605a8.07 8.07 0 0 0 1.695-1.689l-.604-.45zM12.3 2.163c.587.432 1.105.95 1.537 1.537l.604-.45a8.06 8.06 0 0 0-1.69-1.691l-.45.604zM2.163 3.7A7.242 7.242 0 0 1 3.7 2.163l-.45-.604a8.06 8.06 0 0 0-1.691 1.69l.604.45zm12.688.163-.644.387c.377.623.658 1.3.832 2.007l.728-.18a7.931 7.931 0 0 0-.916-2.214zM6.913.831a7.254 7.254 0 0 1 2.172 0l.112-.74a7.985 7.985 0 0 0-2.396 0l.112.74zM2.547 14.64 1 15l.36-1.549-.729-.17-.361 1.548a.75.75 0 0 0 .9.902l1.548-.357-.17-.734zM.786 12.612l.732.168.25-1.073A7.187 7.187 0 0 1 .96 9.74l-.727.18a8 8 0 0 0 .736 1.902l-.184.79zm3.5 1.623-1.073.25.17.731.79-.184c.6.327 1.239.574 1.902.737l.18-.728a7.197 7.197 0 0 1-1.962-.811l-.007.005zM8 1.5a6.502 6.502 0 0 0-6.498 6.502 6.516 6.516 0 0 0 .998 3.455l-.625 2.668L4.54 13.5a6.502 6.502 0 0 0 6.93-11A6.516 6.516 0 0 0 8 1.5"/>
</svg>';

$color=$familiar["color"];

$thstyle="padding:1em; background-color:".$color."; text-align:center;color:white;  ";
$tdstyle="padding: 1em; text-align:center; ";
$tdstylel="padding: 1em; text-align:left";

$alter="white";
?>

<style>


tr:first-child td:first-child { border-top-left-radius: 10px; }
tr:first-child td:last-child { border-top-right-radius: 10px; }
tr:last-child td:first-child { border-bottom-left-radius: 10px; }
tr:last-child td:last-child { border-bottom-right-radius: 10px; }

tr:first-child th:first-child { border-top-left-radius: 0.4em; }
tr:first-child th:last-child { border-top-right-radius: 0.4em; }
tr:last-child th:first-child { border-bottom-left-radius: 10px; }
tr:last-child th:last-child { border-bottom-right-radius: 10px; }

</style>

  
<div class="preguntausuariorespuesta-create" style="margin-top:2em;">


    <h2 style="font-size:3em; margin-bottom: 0.5em"><?=$familiar["nombre"]?> :: <span style="color: <?=$color?>">Respuestas de <?=$comp["competencia"]?></span><span style="color: grey"> - <?=$age["edad"]?> </span></h2>
	
    <h3 style="font-size:2em; margin-bottom: 1em"><span style="color: grey">Contestado a los</span> <?=calcularEdadEpocaTexto($familiar["fechanacimiento"],$fechaid)?> <span style="color: grey">(<?=$fechaid?>)</span> </h3>
	
	
	<table style="width: 90%; border-collapse: separate; ">
		<tr >
			<th style='<?=$thstyle?>'>
				Pregunta
			</th>
			<th style='<?=$thstyle?>'>
				Respuesta
			</th>			
			<th style='<?=$thstyle?>'>
				Comentarios
			</th>
		</tr>
		<?php
		
			foreach($fh as $h){
				$alter=($alter=="white")?"#eee":"white";
				echo "<tr style='background-color: ".$alter."'>";
					echo "<td style='".$tdstylel."'>".$h["pregunta"]["pregunta"]."</td>";
					echo "<td style='".$tdstyle."'>".$h["respuesta"]."</td>";
					echo "<td style='".$tdstyle."'>".(($h["comentario"]=="")?$h["comentario"]:"-")."</td>";

				echo "</tr>";
			}
		
		
		?>
	</table>

	<div style="margin-top:2em; width=100%; text-align: center;">
		<a href="index.php?r=preguntausuariorespuesta%2Fresultadoagehistorico&familiarid=<?=$familiarid?>&edadid=<?=$edadid?>&fechaid=<?=$fechaid?>">
			<div style="padding-left: 2em;border: 3px solid <?=$color?>; width: 10em;float: left;text-align: center;border-radius: 2em;padding-right: 2em;padding-top: 1em;padding-bottom: 1em; color: white; background-color: <?=$color?>">
				Regresar
			</div>
		</a>
	</div>

	
</div>

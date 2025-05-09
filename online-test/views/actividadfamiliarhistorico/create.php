<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Actividadfamiliarhistorico */

$this->title = 'Registrar Actividad Realizada';
$this->params['breadcrumbs'][] = ['label' => 'Actividadfamiliarhistoricos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$color=$familiar["color"];


?>


<div class="actividadfamiliarhistorico-create">

    <h1><?=$familiar["nombre"]?> :: <span style='color:<?=$color?>'>Registrar Actividad</span> <span style="color:grey"> - <?=calcularEdadTexto($familiar["fechanacimiento"], $familiar["semanasprematuro"])?>&nbsp;&nbsp;</span></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'edadm' => $edadm,
        'familiar' => $familiar,
        'familiarid' => $familiarid,
		'actividad' => $actividad,
		'ahs' => $ahs,
         'ahsn' => $ahsn,		
		
    ]) ?>

</div>

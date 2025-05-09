<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Cliente */

$this->title = 'Actualizar Cliente: ' . $model->cliente_id;
$this->params['breadcrumbs'][] = ['label' => 'Clientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->cliente_id, 'url' => ['view', 'id' => $model->cliente_id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="cliente-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formu', [
        'model' => $model,
		'ail' => $ail,
		'gil' => $gil,
		'dil' => $dil,
		'ecl' => $ecl,
		'ecsl' => $ecsl,
		'estact' => $estact,
		'modif' => $modif,
    ]) ?>

</div>

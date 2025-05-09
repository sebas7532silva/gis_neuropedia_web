<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EstatusCliente */

$this->title = 'Update Estatus Cliente: ' . $model->estatus_cliente_id;
$this->params['breadcrumbs'][] = ['label' => 'Estatus Clientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->estatus_cliente_id, 'url' => ['view', 'id' => $model->estatus_cliente_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="estatus-cliente-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

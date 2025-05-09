<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Pago */

$this->title = 'Update Pago: ' . $model->pago_id;
$this->params['breadcrumbs'][] = ['label' => 'Pagos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pago_id, 'url' => ['view', 'id' => $model->pago_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pago-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

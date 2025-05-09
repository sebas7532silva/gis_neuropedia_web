<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Codigodescuento */

$this->title = 'Modificar Código de Descuento Multiuso: ' . $model->codigodescuento_id;
$this->params['breadcrumbs'][] = ['label' => 'Codigodescuentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->codigodescuento_id, 'url' => ['view', 'id' => $model->codigodescuento_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="codigodescuento-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

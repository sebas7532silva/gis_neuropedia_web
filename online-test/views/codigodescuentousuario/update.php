<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Codigodescuentousuario */

$this->title = 'Modificar Código de Descuento por Usuario: ' . $model->codigodescuentousuario_id;
$this->params['breadcrumbs'][] = ['label' => 'Codigodescuentousuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->codigodescuentousuario_id, 'url' => ['view', 'id' => $model->codigodescuentousuario_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="codigodescuentousuario-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

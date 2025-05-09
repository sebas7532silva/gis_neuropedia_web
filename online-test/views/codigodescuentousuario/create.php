<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Codigodescuentousuario */

$this->title = 'Nuevo Código de Descuento por Usuario';
$this->params['breadcrumbs'][] = ['label' => 'Codigodescuentousuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="codigodescuentousuario-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

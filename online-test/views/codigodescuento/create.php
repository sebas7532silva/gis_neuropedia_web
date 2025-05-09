<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Codigodescuento */

$this->title = 'Nuevo Código de Descuento Multiuso';
$this->params['breadcrumbs'][] = ['label' => 'Codigodescuentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="codigodescuento-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

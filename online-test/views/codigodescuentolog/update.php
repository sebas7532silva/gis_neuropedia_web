<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Codigodescuentolog */

$this->title = 'Update Codigodescuentolog: ' . $model->codigodescuentolog;
$this->params['breadcrumbs'][] = ['label' => 'Codigodescuentologs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->codigodescuentolog, 'url' => ['view', 'id' => $model->codigodescuentolog]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="codigodescuentolog-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

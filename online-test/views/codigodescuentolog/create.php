<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Codigodescuentolog */

$this->title = 'Create Codigodescuentolog';
$this->params['breadcrumbs'][] = ['label' => 'Codigodescuentologs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="codigodescuentolog-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

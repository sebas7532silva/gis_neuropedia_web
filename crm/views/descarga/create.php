<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Descarga */

$this->title = 'Create Descarga';
$this->params['breadcrumbs'][] = ['label' => 'Descargas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="descarga-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

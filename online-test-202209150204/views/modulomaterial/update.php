<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Modulomaterial */

$this->title = 'Update Modulomaterial: ' . $model->modulo_id;
$this->params['breadcrumbs'][] = ['label' => 'Modulomaterials', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->modulo_id, 'url' => ['view', 'id' => $model->modulo_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="modulomaterial-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Modulomaterial */

$this->title = 'Cargar Material';
$this->params['breadcrumbs'][] = ['label' => 'Modulomaterials', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="modulomaterial-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'tml' => $tml,
    ]) ?>

</div>

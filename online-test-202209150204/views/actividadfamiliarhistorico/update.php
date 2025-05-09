<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Actividadfamiliarhistorico */

$this->title = 'Update Actividadfamiliarhistorico: ' . $model->actividadhistorico_id;
$this->params['breadcrumbs'][] = ['label' => 'Actividadfamiliarhistoricos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->actividadhistorico_id, 'url' => ['view', 'id' => $model->actividadhistorico_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="actividadfamiliarhistorico-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

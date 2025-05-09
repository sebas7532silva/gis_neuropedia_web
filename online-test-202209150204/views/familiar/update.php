<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Familiar */

$this->title = $model->nombre." :: Cambiar Información";
$this->params['breadcrumbs'][] = ['label' => 'Familiars', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->familiar_id, 'url' => ['view', 'id' => $model->familiar_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="familiar-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		'modo'=>'actualizar',
    ]) ?>

</div>

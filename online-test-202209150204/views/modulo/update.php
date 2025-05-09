<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Modulo */

$this->title = 'Modificar Modulo: ' . $model->titulo;
$this->params['breadcrumbs'][] = ['label' => 'Modulos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->modulo_id, 'url' => ['view', 'id' => $model->modulo_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="modulo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'tproflist' => $tproflist,        
    ]) ?>

</div>

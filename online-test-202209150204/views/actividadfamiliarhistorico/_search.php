<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ActividadfamiliarhistoricoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="actividadfamiliarhistorico-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'actividadhistorico_id') ?>

    <?= $form->field($model, 'actividad_id') ?>

    <?= $form->field($model, 'familiar_id') ?>

    <?= $form->field($model, 'fecha') ?>

    <?= $form->field($model, 'notas') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

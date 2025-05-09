<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ClienteSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cliente-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'cliente_id') ?>

    <?= $form->field($model, 'nombre') ?>

    <?= $form->field($model, 'prim_ap') ?>

    <?= $form->field($model, 'seg_ap') ?>

    <?= $form->field($model, 'cony_nombre') ?>

    <?php // echo $form->field($model, 'cony_prim_ap') ?>

    <?php // echo $form->field($model, 'cony_seg_ap') ?>

    <?php // echo $form->field($model, 'num_int') ?>

    <?php // echo $form->field($model, 'vcv') ?>

    <?php // echo $form->field($model, 'monto_credito') ?>

    <?php // echo $form->field($model, 'comentarios') ?>

    <?php // echo $form->field($model, 'otro_estatus') ?>

    <?php // echo $form->field($model, 'asesor') ?>

    <?php // echo $form->field($model, 'gerente') ?>

    <?php // echo $form->field($model, 'director_hipotecario') ?>

    <?php // echo $form->field($model, 'estatus_cliente_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

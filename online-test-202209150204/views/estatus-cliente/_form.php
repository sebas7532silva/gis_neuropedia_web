<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EstatusCliente */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="estatus-cliente-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'estatus')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'etapa')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'activo')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

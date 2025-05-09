<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ExamenpreguntaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="examenpregunta-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'pregunta_id') ?>

    <?= $form->field($model, 'examen_id') ?>

    <?= $form->field($model, 'edad_id') ?>

    <?= $form->field($model, 'competencia_id') ?>

    <?= $form->field($model, 'pregunta') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

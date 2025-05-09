<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Examenpregunta */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="examenpregunta-form">

    <?php $form = ActiveForm::begin(); ?>
<div style="display:none">
    <?= $form->field($model, 'examen_id')->hiddenInput() ?>

    <?= $form->field($model, 'edad_id')->textInput() ?>

    <?= $form->field($model, 'competencia_id')->textInput() ?>
	
</div>

    <?= $form->field($model, 'pregunta')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

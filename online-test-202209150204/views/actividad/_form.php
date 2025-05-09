<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Actividad */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="actividad-form">

    <?php $form = ActiveForm::begin(); ?>

<div style="display:none;">
    <?= $form->field($model, 'examen_id')->textInput() ?>

    <?= $form->field($model, 'edad_inferior_id')->textInput() ?>

    <?= $form->field($model, 'edad_superior_id')->textInput() ?>
	
</div>

    <?= $form->field($model, 'actividad')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

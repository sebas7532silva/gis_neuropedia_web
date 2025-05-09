<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Cursoprofesor */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cursoprofesor-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'curso_id')->dropDownList($tcursolist,['prompt'=>'--Seleccionar--'])->label('Curso') ?>

    <?= $form->field($model, 'email')->dropDownList($tproflist,['prompt'=>'--Seleccionar--'])->Label('Profesor') ?>

    <?= $form->field($model, 'titularidad')->dropDownList(["TITULAR"=>"TITULAR","INVITADO"=>"INVITADO"],['prompt'=>'--Seleccionar--'])->label('Titularidad') ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

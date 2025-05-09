<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Usuario */
/* @var $form yii\widgets\ActiveForm */
?>

    <?php 
	
		if($message==""){
			$form = ActiveForm::begin([
				'options' => ['enctype' => 'multipart/form-data'],
				'fieldConfig' => ['errorOptions' => ['encode' => false, 'class' => 'help-block']] 
   ]); ?>
   

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <div class="form-group" style="margin-top:1em">
        <?= Html::submitButton('Recuperar Contraseña', ['class' => 'btn btn-success', 'id'=>'btnregistrarme']) ?>
    </div>

    <?php ActiveForm::end(); 
		}else{
	?>
			<div style="background-color:green; padding: 2em; color:black; border-radius:2em; text-align:center; color:white; font-size:1em"><?=$message?></div>

	<?php
			
		}
	?>

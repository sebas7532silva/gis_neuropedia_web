<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'CRM';

?>

<div class="cliente-form">

    <?php $form = ActiveForm::begin(); ?>
	
	<hr></hr>
	<h3>Ingresar</h3>
	<hr></hr>
	<?php
		if($error!=""){
	?>
		<div class="alert alert-danger" role="alert">
	<?php
		}else{
	?>
		<div>
	<?php
		}
	?>
		<?=$error?>
	</div>
	<div class="form-group">
		<label for="nomuser">Usuario</label>
		<input name="nomuser" class="form-control">
	</div>
	<div class="form-group">
		<label for="pass">Password</label>
		<input type="password" name="pass" class="form-control">
	</div>
    <div class="form-group">
        <?= Html::submitButton('Ingresar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
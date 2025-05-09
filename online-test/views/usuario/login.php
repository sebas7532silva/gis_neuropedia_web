<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Ingresar a Neurobaby';

?>
<style>
	body{
	}
</style>
<div class="cliente-form" style="background-color: #d7c4d8; padding: 20px; border-radius: 2em">

    <?php $form = ActiveForm::begin(); ?>
	<div style="width: 100%; text-align: center; padding-bottom:2em">			
		<h1 style="color: white;">¡Bienvenido(a) a Neurobaby!</h1>
	</div>
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
	
	<div style="width:100%; padding-left:2em; padding-right:2em; height:22em; ">
		<?php $char=["fireman","lawyer"]; ?>
		<div class="loginleft">
			<img src="../web/images/<?=$char[rand(0,1)]?>.png" style="height: 20em"/>
		</div>
		
		<div class="loginright">
		
			<div class="form-group" style="width:100%; ">
				<label for="nomuser">Email<a class="btn btn-link" style="color:dark-blue; text-decoration:none" href="index.php?r=usuario/recover">(Olvidé mi Contraseña)</a></label>
				<input name="nomuser" class="form-control loginfcontrol" >
			</div>
			<div class="form-group" style="width:100%; ">
				<label for="pass">Password</label>
				<input type="password" name="pass" class="form-control loginfcontrol" >
			</div>
				
			<div  style="width:100%; ">			
				<?= Html::submitButton('Ingresar', ['class' => 'btn btn-success']) ?>
				<a class="btn btn-link" style="color:dark-grey; text-decoration:underline" href="index.php?r=usuario/createcliente">Crear Nueva Cuenta</a>
			</div>
			
		</div>
	</div>
	
    <?php ActiveForm::end(); ?>

</div>
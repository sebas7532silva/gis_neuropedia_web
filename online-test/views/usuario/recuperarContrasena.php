<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Usuario */

$this->title = 'Recuperar Contraseña';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="usuario-recuperarContrasena">

    <h1 style="width:100%; text-align:center; color: black; margin-bottom:1em"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formRecuperarContrasena', [
        'message' => $message,
		'model'=>$model,
    ]) ?>

</div>

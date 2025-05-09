<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Usuario */

$this->title = '¡BIENVENIDA/O A NEUROBABY!';
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="usuario-create">

	<div style="text-align: center; width:100%">
		<img src="https://dragisneuropedia.com/wp-content/uploads/2019/09/Sin-t%C3%ADtulo-1-5.png" type="video/mp4">
	</div>


    <h1 style="width:100%; text-align:center; color: #fc005f; margin-bottom:1em"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formcliente', [
        'model' => $model,
    ]) ?>

</div>

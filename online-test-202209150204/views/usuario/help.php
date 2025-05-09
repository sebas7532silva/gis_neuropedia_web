<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UsuarioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ayuda Neurobaby';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usuario-help">

    <h1><?= Html::encode($this->title) ?></h1>
	
	<?php if(isset($_SESSION["usuario"])){ ?>
		<p style="margin-top:1em; margin-bottom:1em; font-size: 1em; color: grey"><strong>Dudas, Aclaraciones o Sugerencias (ya tengo mi cuenta): </strong>En caso de tener cualquier duda, aclaración o sugerencia favor de enviar un mail a neurobaby.dragis@gmail.com. Es muy importante que si ya tienes tu cuenta, el correo se enviado desde el email que registraste, en este caso <strong><?=$_SESSION["usuario"]?></strong>.</p>
	<?php }else{ ?>
		<p style="margin-top:1em; margin-bottom:1em; font-size: 1em; color: grey"><strong>Dudas, Aclaraciones o Sugerencias (cuentas nuevas y registro): </strong>En caso de tener cualquier duda, aclaración o sugerencia favor de enviar un mail a neurobaby.dragis@gmail.com. </p>
	<?php } ?>

</div>

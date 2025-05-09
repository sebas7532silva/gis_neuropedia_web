<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;


AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
	<style>
		.navbar-brand{
			font-size: 1em;
		}
		@media (max-width: 900px) {
			.navbar-brand{
				font-size: 0.8em;
			}
		}
	</style>
	
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'CRM Doctora Gisela',
        'brandUrl' => Yii::$app->homeUrl.'?r=cliente/index',
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
	
	$admins=["DIRECCION GENERAL","MESA DE CONTROL"];
	
	if(isset($_SESSION["usuario"])){
		if(in_array($_SESSION['perfil'],$admins)){
			echo Nav::widget([
				'options' => ['class' => 'navbar-nav navbar-right'],
				'items' => [
					['label' => 'Recursos', 'url' => ['/recurso/index']],
					['label' => 'Reporte de Descargas', 'url' => ['/descarga/index']],
					['label' => 'Cambio Password', 'url' => ['/usuario/changepass']],

					['label' => 'Logout', 'url' => ['/usuario/logout']]					
				],
			]);
		}else{
			echo Nav::widget([
				'options' => ['class' => 'navbar-nav navbar-right'],
				'items' => [
					['label' => 'Cambio Password', 'url' => ['/usuario/changepass']],
					['label' => 'Clientes', 'url' => ['/cliente/index']],
					['label' => 'Logout', 'url' => ['/usuario/logout']]										
				],
			]);			
		}
	}else{
		echo Nav::widget([
			'options' => ['class' => 'navbar-nav navbar-right'],
			'items' => [
				['label' => 'Login', 'url' => ['/usuario/login']]										
			],
		]);					
	}
	

    NavBar::end();		
    
    ?>

    <div class="container">
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

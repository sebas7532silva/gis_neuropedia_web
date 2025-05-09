<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\models\Modulocliente;


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
	<link rel="icon" type="image/x-icon" href="../web/images/favicon.png">
	
	<!-- Meta Pixel Code -->
	<script>
	!function(f,b,e,v,n,t,s)
	{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
	n.callMethod.apply(n,arguments):n.queue.push(arguments)};
	if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
	n.queue=[];t=b.createElement(e);t.async=!0;
	t.src=v;s=b.getElementsByTagName(e)[0];
	s.parentNode.insertBefore(t,s)}(window, document,'script',
	'https://connect.facebook.net/en_US/fbevents.js');
	fbq('init', '1154343998443322');
	fbq('track', 'PageView');
	</script>
	<noscript><img height="1" width="1" style="display:none"
	src="https://www.facebook.com/tr?id=1154343998443322&ev=PageView&noscript=1"
	/></noscript>
	<!-- End Meta Pixel Code -->	
	
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => "<span style='color: #dddddd; font-size: 2em'>Neurobaby</span>",
        'brandUrl' => Yii::$app->homeUrl.'?r=familiar/index',
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
	
	$admins=["PROFESOR","ADMIN"];
	$horas=0;
	if(isset($_SESSION["perfil"])&&$_SESSION["perfil"]=="ESTUDIANTE"){
		$moduloscliente = Modulocliente::find()->where(['cliente' => $_SESSION["usuario"],"estatus"=>"CARRITO"])->all();
		foreach($moduloscliente as $mc){
			$horas+=$mc->modulo0->horas_teoricas;
		}
	}
	
	if(isset($_SESSION["usuario"])){
		if(in_array($_SESSION['perfil'],$admins)){
			echo Nav::widget([
				'options' => ['class' => 'navbar-nav navbar-right'],
				'items' => [
//					['label' => 'Cambio Password', 'url' => ['/usuario/changepass']],
//					['label' => 'Usuarios', 'url' => ['/usuario/index']],
//					['label' => 'Examenes', 'url' => ['/examen/index']],
//					['label' => 'Pagos', 'url' => ['/curso/index']],
//					['label' => 'Estadísticas de Uso', 'url' => ['/modulomaterial/index']],
					['label' => 'Cerrar Sesión', 'url' => ['/usuario/logout']]					
				],
			]);
		}else{
			if(($_SESSION["horasdisponibles"]>0)){
				echo Nav::widget([
					'options' => ['class' => 'navbar-nav navbar-right'],
					'items' => [
	//					['label' => 'Horas Totales: '.$_SESSION["horasdisponibles"]],
	//					['label' => 'Mis Cursos', 'url' => ['/site/miscursos']],
						['label' => 'Ayuda', 'url' => ['/usuario/help'], 'linkOptions' => ['target' => '_blank']],			
						['label' => 'Comprar Examen Extra', 'url' => ['/site/carrito']],
						['label' => 'Cambio Password', 'url' => ['/usuario/changepass']],
						['label' => 'Cerrar Sesión', 'url' => ['/usuario/logout']]										
					],
				]);			
			}else{
				echo Nav::widget([
					'options' => ['class' => 'navbar-nav navbar-right'],
					'items' => [
	//					['label' => 'Horas Totales: '.$_SESSION["horasdisponibles"]],
	//					['label' => 'Mis Cursos', 'url' => ['/site/miscursos']],
//						['label' => 'Comprar Examen Extra', 'url' => ['/site/carrito']],
						['label' => 'Ayuda', 'url' => ['/usuario/help'], 'linkOptions' => ['target' => '_blank']],			
						['label' => 'Cambio Password', 'url' => ['/usuario/changepass']],
						['label' => 'Cerrar Sesión', 'url' => ['/usuario/logout']]										
					],
				]);							
			}
		}
	}else{
		echo Nav::widget([
			'options' => ['class' => 'navbar-nav navbar-right'],
			'items' => [
				['label' => 'Ayuda', 'url' => ['/usuario/help'], 'linkOptions' => ['target' => '_blank']],			
				['label' => 'Ingresar', 'url' => ['/usuario/login']]
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

<!--<footer class="footer">
    <div class="container">
    </div>
</footer>-->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

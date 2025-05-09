<?php


use yii\helpers\Html;
use yii\grid\GridView;
use \yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $searchModel app\models\UsuarioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Administrar';
$this->params['breadcrumbs'][] = $this->title;
$textoModulos="Conocer un poco más";
$paginaSiguiente="cursogratis";
?>
<div class="usuario-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <br/>
            <?= Html::a('Modificar Actividades', ['actividad/adminindex'], ['class' => 'btn btn-success']) ?>
            <?= Html::a('Modificar Preguntas', ['examenpregunta/index'], ['class' => 'btn btn-success']) ?>
            <?= Html::a('Gestionar Códigos Multiuso', ['codigodescuento/index'], ['class' => 'btn btn-success']) ?>
            <?= Html::a('Gestionar Códigos Por Usuario', ['codigodescuentousuario/index'], ['class' => 'btn btn-success']) ?>

</div>
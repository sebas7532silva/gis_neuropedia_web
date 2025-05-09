<?php


use yii\helpers\Html;
use yii\grid\GridView;
use \yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $searchModel app\models\UsuarioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $curso->titulo;
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="usuario-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <br>
    <?php 
            echo "<h2>".$modulo->titulo."</h2>";

            echo "<p>".$curso->descripcion."</p><br/>";

            echo '<iframe width="620" height="515" src="https://www.youtube.com/embed/'.$modulo->video.'""></iframe>';

            echo "<br/><br/><h3>Ejercicio</h3><br/><br/>";

            echo "<p>".$modulo->ejercicios."</p>";

        echo "<br/><br/><h3>Materiales</h3><br/><br/><ul>";
        foreach($materiales as $material){
            echo "<li><a href='uploads/".$material->filename."'>".$material->material."</a></li>";
        }
        echo "</ul>";

        echo "<br/><h3>Horas</h3><br/><ul>";
        echo "<li> Horas Teóricas: ".$modulo->horas_practicas."</li>";
        echo "<li> Horas Prácticas: ".$modulo->horas_teoricas."</li>";
        echo "</ul>";
    ?>

</div>
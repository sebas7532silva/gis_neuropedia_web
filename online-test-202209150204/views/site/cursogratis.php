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
    <ul>
    <?php 
        if(sizeof($modulos)>0){
            echo "<h2>".$modulos[0]->titulo."</h2>";

            echo "<p>".$curso->descripcion."</p>";

            echo '<iframe width="620" height="515" src="https://www.youtube.com/embed/'.$modulos[0]->video.'""></iframe>';

            echo "<br/><br/><h3>Ejercicio</h3><br/><br/><ul>";

            echo "<p>".$modulos[0]->ejercicios."</p>";

        }else{
            echo "No hay ningún módulo gratis disponible";
        }
        echo "<br/><br/><h3>Materiales</h3><br/><br/><ul>";
        foreach($materiales as $material){
            echo "<li><a href='uploads/".$material->filename."'>".$material->material."</a></li>";
        }
        echo "</ul>";

        echo "<br/><br/><h3>Horas</h3><br/><br/><ul>";
        if(sizeof($modulos)>0){
            echo "<p> Horas Teóricas: ".$modulos[0]->horas_practicas."</p>";
            echo "<p> Horas Prácticas: ".$modulos[0]->horas_teoricas."</p>";
        }
    ?>
    </ul>

</div>
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
    <h2>Sesiones</h2>
    <br/>
    <?php         
        if(sizeof($modulos)>0){
            foreach($modulos as $modulo){
                $clase="btn-success";
                $texto="Agregar a Carrito";
                $sesion="sesiones";
                foreach($moduloscliente as $mc){
                    if($modulo->modulo_id==$mc->modulo){
                        if($mc->estatus=="CARRITO"){
                            $clase="btn";
                            $texto="Quitar del Carrito";        
                        }elseif($mc->estatus=="PAGADO"){
                            $clase="btn-primary";
                            $texto="Ingresar";
                            $sesion="sesion";                                    
                        }
                    }
                }
                echo "<h4>".$modulo->titulo."</h4> Horas Prácticas: ".$modulo->horas_practicas.", Horas Teóricas: ".$modulo->horas_teoricas."<br/><br/> <a href='index.php?r=site/".$sesion."&curso_id=".$_GET["curso_id"]."&modulo_id=".$modulo->modulo_id."' class='btn ".$clase." btn-sm'>".$texto."</a><br/><br/>";
            }
        }
    ?>
    </ul>

</div>
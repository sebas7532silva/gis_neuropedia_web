<?php


use yii\helpers\Html;
use yii\grid\GridView;
use \yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $searchModel app\models\UsuarioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Mis Sesiones";
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="usuario-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <br/>
    <table style="border:1px solid black; border-radius: 5px;">
        <tr>
            <th style="border:1px solid black; padding:10px;">
                Módulo
            </th>
            <th style="border:1px solid black; padding:10px">
                Sesión
            </th>
            <th style="border:1px solid black; padding:5px">
                
            </th>
        </tr>
    <?php         
        if(sizeof($moduloscliente)>0){
            foreach($moduloscliente as $mc){
                $clase="btn-primary";
                $texto="Ingresar";        
                echo "<tr>";
                echo "<td  style='border:1px solid black; padding:10px'>".$mc->modulo0->curso->titulo."</td>";
                echo "<td style='border:1px solid black; padding:10px'>".$mc->modulo0->titulo."</td>";
                echo "<td style='border:1px solid black; padding:5px'><a href='index.php?r=site/sesion&modulo_id=".$mc->modulo0->modulo_id."' class='btn ".$clase." btn-sm'>".$texto."</a></td>";
            }
        }
    ?>
    </table>

</div>
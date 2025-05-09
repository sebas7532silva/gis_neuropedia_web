<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Actividades Sugeridas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="actividad-index">

    <h1><?= Html::encode($this->title) ?></h1>



    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'edadInferior.edad',
            'edadSuperior.edad',
            'actividad:ntext',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{myButton}',  // the default buttons + your custom button
            'buttons' => [
                'myButton' => function($url, $model, $key) {     // render your custom button
					$pagina="";
					if(isset($_GET["page"])){
						$pagina="&page=".$_GET["page"];
					}
                    return Html::a("editar","index.php?r=actividad/update&id=".$model->actividad_id.$pagina);
                }
            ]],

        ],
    ]); ?>


</div>

<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ExamenpreguntaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Preguntas';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="examenpregunta-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => null,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'edad.edad',
            'competencia.competencia',
            'pregunta:ntext',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{myButton}',  // the default buttons + your custom button
            'buttons' => [
                'myButton' => function($url, $model, $key) {     // render your custom button
					$pagina="";
					if(isset($_GET["page"])){
						$pagina="&page=".$_GET["page"];
					}
                    return Html::a("editar","index.php?r=examenpregunta/update&id=".$model->pregunta_id.$pagina);
                }
            ]],
        ],
    ]); ?>


</div>

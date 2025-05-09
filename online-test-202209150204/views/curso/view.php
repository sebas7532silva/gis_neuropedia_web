<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Curso */

$this->title = $model->titulo;
$this->params['breadcrumbs'][] = ['label' => 'Cursos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="curso-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Modificar', ['update', 'id' => $model->curso_id], ['class' => 'btn btn-primary']) ?>
        
    </p>

    <p>
        <h2>Profesores</h2>
        <ul>
            <?php foreach($ptitsl as $prof){ ?>
                <li> <?= $prof ?> </li>
            <?php } ?>
        </ul>
    </p>
    <br/>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'descripcion:ntext',
            'tipo.nombre',
            'ubicacion:ntext',
            'sesiones',
            'horas',
            'presentacion:ntext',
            'objetivos:ntext',
            'contenido:ntext',
            'unidades:ntext',
            'acreditacion:ntext',
            'bibliografia:ntext',
        ],
    ]) ?>

    <p>
        <?= Html::a('Añadir Sesión', ['/modulo/index', 'curso_id' => $model->curso_id], ['class' => 'btn btn-success']) ?>
    </p>    

</div>

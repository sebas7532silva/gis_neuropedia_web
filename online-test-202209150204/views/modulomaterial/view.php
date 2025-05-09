<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Modulomaterial */

$this->title = $model->material;
$this->params['breadcrumbs'][] = ['label' => 'Modulomaterials', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="modulomaterial-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->material], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Eliminar este material?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'modulo_id',
            'material',
        ],
    ]) ?>

</div>

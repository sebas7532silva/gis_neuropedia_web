<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Examenpregunta */

$this->title = 'Create Examenpregunta';
$this->params['breadcrumbs'][] = ['label' => 'Examenpreguntas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="examenpregunta-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

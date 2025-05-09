<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Familiar */

$this->title = 'Nuevo Integrante de mi Familia';
$this->params['breadcrumbs'][] = ['label' => 'Familiars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="familiar-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		'modo'=>'crear',
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Cursoprofesor */

$this->title = 'Asignar';
$this->params['breadcrumbs'][] = ['label' => 'Cursoprofesors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cursoprofesor-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'tproflist' => $tproflist,
        'tcursolist' => $tcursolist,        
    ]) ?>

</div>

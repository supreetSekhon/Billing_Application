<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Proposals $model */

$this->title = 'Create Proposals';
$this->params['breadcrumbs'][] = ['label' => 'Proposals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="proposals-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\TblInvoice $model */

$this->title = 'Create Invoice';
$this->params['breadcrumbs'][] = ['label' => 'Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-invoice-create">

          <h1><?= Html::encode($this->title) ?></h1>
       
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

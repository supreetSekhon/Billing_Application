<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\TblInvoice $model */

$this->title = 'Update Invoice: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tbl-invoice-update">

  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-lg-6">
          <h4 class="mb-0"><?= Html::encode($this->title) ?></h4>
        </div>
        <div class="col-lg-6 text-end">
          <?= Html::a('Back to List', ['index'], ['class' => 'btn btn-primary btn-sm']) ?>
        </div>
      </div>
    </div>
  </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

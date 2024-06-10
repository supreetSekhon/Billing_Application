<?php

use app\models\Invoices;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\models\Clients;
/** @var yii\web\View $this */
/** @var app\models\TblInvoiceSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Invoices';
$this->params['breadcrumbs'][] = $this->title;
$template = ' {edit} {delete}';
?>
<div class="card mt-3">
  <div class="card-header bg-light">
    <div class="row align-items-center">
      <div class="col">
        <h5 class="mb-0"><?= Html::encode($this->title) ?></h5>
      </div>
      <div class="col-auto">
        <?php
        // if(isset($menuaccess) && $menuaccess->create_crud == 1){
          ?>
          <a class="btn btn-primary btn-sm" href="<?= Url::to(["create"]) ?>">
            <span class="fas fa-pencil-alt fs--2 me-1"></span>Create New Invoice
          </a>
          <?php
        // }
        ?>
      </div>
    </div>
  </div>
  <div class="card-body border-top">
    <div id="msg">
      <?php
      if(Yii::$app -> session -> getFlash('success')!=null){
        ?>
        <div class="alert alert-primary alert-dismissable mb-3">
          <?= Yii::$app -> session -> getFlash('success'); ?>
        </div>
        <?php
      }else if(Yii::$app -> session -> getFlash('error')!=null){
        ?>
        <div class="alert alert-danger alert-dismissable mb-3">
          <?= Yii::$app -> session -> getFlash('error'); ?>
        </div>
        <?php
      }
      ?>
    </div>
<div class="tbl-invoice-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            [
              'class' => ActionColumn::className(),
              'template' => $template,
              'buttons' => [
                'edit' => function ($url, $model) {
                  return Html::a('<i class=" fas fa-pencil-alt text-primary"></i>', $url, [
                    'title' => Yii::t('app', 'edit'),
                  ]);
                },
                'delete' => function ($url, $model, $key) {
                  $options = [
                    'title' => Yii::t('yii', 'Delete'),
                    'aria-label' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'data-method' => 'post',
                    'data-pjax' => '0',
                  ];
                  return Html::a('<i class="fas fa-trash text-danger"></i>', $url, $options);
                }
              ],
              'urlCreator' => function ($action, $model, $key, $index) {
                if ($action === 'edit') {
                  $url ='index.php?r=invoice/update&id='.$model->id;
                  return $url;
                }
                if ($action === 'delete') {
                  $url ='index.php?r=invoice/delete&id='.$model->id;
                  return $url;
                }
              }
            ],
            // 'id',
            'invoice_number',
            // 'fk_customer_id',
            [
              'attribute' => 'fk_customer_id',
              'label' => 'Customer',
              'value' => function($dataProvider){
                $getcustomer = Clients::find()->where(['id'=>$dataProvider->fk_customer_id,'status'=>1])->one();
                if(isset($getcustomer) && $getcustomer->name != ""){
                  return $getcustomer->name;
                }else{
                  return "(not set)";
                }
              }
            ],
            'invoice_date',
            'due_date',
            'total_amount',
            //'comments:ntext',
            //'status',
            //'ip',
            //'created_at',
            //'created_by',
            //'updated_at',
            //'updated_by',
            // [
            //     'class' => ActionColumn::className(),
            //     'urlCreator' => function ($action, TblInvoice $model, $key, $index, $column) {
            //         return Url::toRoute([$action, 'id' => $model->id]);
            //      }
            // ],
        ],
    ]); ?>


</div>
</div>
</div>

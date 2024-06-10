<?php

use app\models\Proposals;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\models\StatusValues;
use app\models\Clients;
use app\models\Users;
/** @var yii\web\View $this */
/** @var app\models\ProposalsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Proposals';
$this->params['breadcrumbs'][] = $this->title;
//---get clients ------
$allclients = Clients::find()->where(['status'=>1])->all();
$clientsarr = ArrayHelper::map($allclients,'id','name');
//------get users -----
$allusers = Users::find()->where(['status'=>1])->all();
$usersarr = ArrayHelper::map($allusers,'id','username');
//------get status -----
$allstatus = StatusValues::find()->where(['status'=>1])->all();
$statusarr = ArrayHelper::map($allstatus,'id','status_name');
?>
<div class="proposals-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Proposals', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            // 'fk_client_id',
            [
                'attribute' => 'fk_client_id',
                'label' => 'Client',
                'value' => function($dataProvider){
                    $getclient = Clients::find()->where(['id'=>$dataProvider->fk_client_id])->andWhere('status != 0')->one();
                    if(isset($getclient) && $getclient->name != ""){
                        return $getclient->name;
                    }else{
                        return "(not set";
                    }
                }
            ],
            // 'fk_user_id',
            [
                'attribute' => 'fk_user_id',
                'label' => 'Proposal Prepared By',
                'value' => function($dataProvider){
                    $getusers = Users::find()->where(['id'=>$dataProvider->fk_user_id])->andWhere('status != 0')->one();
                    if(isset($getusers) && $getusers->username != ""){
                        return $getusers->username;
                    }else{
                        return "(not set";
                    }
                }
            ],
            'proposal_text:ntext',
            // 'fk_status_id',
            [
                'attribute' => 'fk_status_id',
                'label' => 'Status',
                'value' => function($dataProvider){
                    $getstatus = StatusValues::find()->where(['id'=>$dataProvider->fk_status_id])->andWhere('status != 0')->one();
                    if(isset($getstatus) && $getstatus->status_name != ""){
                        return $getstatus->status_name;
                    }else{
                        return "(not set";
                    }
                }
            ],
            //'created_at',
            //'created_by',
            //'ip',
            //'status',
            //'updated_at',
            //'updated_by',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Proposals $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>

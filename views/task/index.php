<?php

use app\models\Tasks;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Users;
use app\models\TaskStatusValues;
/** @var yii\web\View $this */
/** @var app\models\TasksSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Tasks';
$this->params['breadcrumbs'][] = $this->title;
//------get users -----
$allusers = Users::find()->where(['status'=>1])->all();
$usersarr = ArrayHelper::map($allusers,'id','username');
//------get status -----
$allstatus = TaskStatusValues::find()->where(['status'=>1])->all();
$statusarr = ArrayHelper::map($allstatus,'id','status_name');
?>
<div class="tasks-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Tasks', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            
            'title',
            'description:ntext',
            // 'assigned_to',
            [
                'attribute' => 'assigned_to',
                'label' => 'Assigned To',
                'value' => function($dataProvider){
                    $getusers = Users::find()->where(['id'=>$dataProvider->assigned_to])->andWhere('status != 0')->one();
                    if(isset($getusers) && $getusers->username != ""){
                        return $getusers->username;
                    }else{
                        return "(not set";
                    }
                }
            ],
            'due_date',
            // 'status_id',
            [
                'attribute' => 'status_id',
                'label' => 'Status',
                'value' => function($dataProvider){
                    $getstatus = TaskStatusValues::find()->where(['id'=>$dataProvider->status_id])->andWhere('status != 0')->one();
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
                'urlCreator' => function ($action, Tasks $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>

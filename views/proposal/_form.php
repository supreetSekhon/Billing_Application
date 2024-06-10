<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\StatusValues;
use app\models\Clients;
use app\models\Users;
/** @var yii\web\View $this */
/** @var app\models\Proposals $model */
/** @var yii\widgets\ActiveForm $form */
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

<div class="proposals-form">
    
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-lg-4">
            <?= $form->field($model, 'fk_client_id')->dropDownList($clientsarr,['prompt'=>'Select']) ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'fk_user_id')->dropDownList($usersarr,['prompt'=>'Select']) ?>
        </div>
        <div class="col-lg-4">
        <?= $form->field($model, 'fk_status_id')->dropDownList($statusarr,['prompt'=>'Select']) ?>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-lg-12">
            <?= $form->field($model, 'proposal_text')->textarea(['rows' => 16]) ?>
        </div>
    </div>
    <div class="form-group mt-2">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<?php
$this->registerJs('
var quill = new Quill("#proposals-proposal_text", {
    theme: \'snow\'
});
');
?>
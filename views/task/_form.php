<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Users;
use app\models\TaskStatusValues;
/** @var yii\web\View $this */
/** @var app\models\Tasks $model */
/** @var yii\widgets\ActiveForm $form */
//------get users -----
$allusers = Users::find()->where(['status'=>1])->all();
$usersarr = ArrayHelper::map($allusers,'id','username');
//------get status -----
$allstatus = TaskStatusValues::find()->where(['status'=>1])->all();
$statusarr = ArrayHelper::map($allstatus,'id','status_name');
?>

<div class="tasks-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-lg-3">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'assigned_to')->dropDownList($usersarr,['prompt'=>'Select']) ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'due_date')->textInput() ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'status_id')->dropDownList($statusarr,['prompt'=>'Select']) ?>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-lg-12">
            <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
        </div>
    </div>
    <div class="form-group mt-3">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr("#tasks-due_date", {
            dateFormat: "Y-m-d",
            allowInput: true
        });
    });
</script>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<?php
$this->registerJs('
var quill = new Quill("#tasks-description", {
    theme: \'snow\'
});
');
?>
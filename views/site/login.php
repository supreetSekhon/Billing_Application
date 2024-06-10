<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Login</h3></div>
                                    <div class="card-body">
                                    <?php $form = ActiveForm::begin([
                                            'id' => 'login-form',
                                            'fieldConfig' => [
                                                'template' => "{label}\n{input}\n{error}",
                                                'labelOptions' => ['class' => 'col-lg-12 col-form-label mr-lg-3'],
                                                'inputOptions' => ['class' => 'col-lg-3 form-control'],
                                                'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
                                            ],
                                        ]); ?>

                                        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                                        <?= $form->field($model, 'password')->passwordInput() ?>

                                        <?= $form->field($model, 'rememberMe')->checkbox([
                                            'template' => "<div class=\"custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
                                        ]) ?>

                                        <div class="form-group">
                                            <div>
                                                <?= Html::submitButton('Login', ['class' => 'btn btn-primary w-100', 'name' => 'login-button']) ?>
                                            </div>
                                        </div>

                                        <?php ActiveForm::end(); ?>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; 2024</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

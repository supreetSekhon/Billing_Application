<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
$passwordHash = Yii::$app->getSecurity()->generatePasswordHash('admin123#');
$authKey = Yii::$app->getSecurity()->generateRandomString();

// echo "Password Hash: $passwordHash<br>";
// echo "Auth Key: $authKey\n";

?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        This is the About page. You may modify the following file to customize its content:
    </p>

    <code><?= __FILE__ ?></code>
</div>

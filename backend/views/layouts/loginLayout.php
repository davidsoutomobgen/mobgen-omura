<?php
use backend\assets\LoginAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

LoginAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <!-- Theme style -->
    <link href="/css/AdminLTE.min.css" rel="stylesheet">
    <!-- iCheck -->
    <link rel="stylesheet" href="../../plugins/iCheck/square/blue.css">
    <?php $this->head() ?>
</head>
<body class="hold-transition login-page">
    <?php $this->beginBody() ?>
    <div class="wrap">
        <div class="container">
        <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <!-- <p class="pull-left">&copy; MOBGEN <?= date('Y') ?></p> -->
            <p class="pull-right">&copy; MOBGEN <?= date('Y') ?></p>
            <!-- <p class="pull-right"><?= Yii::powered() ?></p> -->
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

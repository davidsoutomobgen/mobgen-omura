<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <div class="wrap">
        <div class="grid-container">
            <div class="site-logo">
                <h1 class="logo">
                    <a href="https://mobgen.com/" title="MOBGEN" rel="home">
                        <img src="https://mobgen.com/wp-content/themes/brooklyn/images/mobgen_logo_top_black.png" alt="MOBGEN logo">
                    </a>
                </h1>
            </div>
        </div>

        <div class="container">
            <?= Breadcrumbs::widget([]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
        <p class="pull-left"></p>
        <p class="pull-right">&copy; Copyright MOBGEN, mobile solution specialist since 2009</p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

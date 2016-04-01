<?php Pjax::begin(); ?>
<?= Html::a("Show Time", ['site/time'], ['class' => 'btn btn-lg btn-primary']) ?>
<?= Html::a("Show Date", ['site/date'], ['class' => 'btn btn-lg btn-success']) ?>
<h1>It's: <?= $response ?></h1>
<?php Pjax::end(); ?>
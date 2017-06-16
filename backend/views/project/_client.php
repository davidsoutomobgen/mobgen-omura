<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PoItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Client';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-index">
<?php echo 'kk'; die; ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            'first_name',
            'last_name',
            'email',
            'phone',
            'company',
            'job_title',
        ],
    ]); ?>

</div>
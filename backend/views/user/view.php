<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use common\models\User;
use backend\models\Builds;
use backend\models\Utils;


/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->first_name . ' ' . $model->last_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">
    <section class="content">
        <h1><?= Html::encode($this->title) ?></h1>

        <?= Yii::$app->session->getFlash('error'); ?>
        <?php
        foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
            echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
        }
        ?>
        <div class="row">
            <div class="col-md-3">
                <?php
                    echo $this->render('_block', [
                                        'model' => $model,
                                        'bordercolor' => 'success',
                                        'header' => false,
                    ]);
                ?>
            </div>
            <?php
            if (!isset($_POST['User'])) {
                $user_tab = 'active';
                $pass_tab = '';
            } 
            else {
                $user_tab = '';
                $pass_tab = 'active';
            }
            
            ?>
            <div class="col-md-9">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="<?= $user_tab;?>"><a href="#activity" data-toggle="tab" aria-expanded="true">User Information</a></li>
                        <!-- <li class=""><a href="#timeline" data-toggle="tab" aria-expanded="false">Timeline</a></li> -->
                        <li class="<?= $pass_tab;?>"><a href="#password" data-toggle="tab" aria-expanded="false">Password</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane <?= $user_tab;?>" id="activity">
                            <h3 class="left"><?= Yii::t('app', 'User');?></h3>
                            <p class="right">
                                <?= Html::a(Yii::t('app', 'Edit'), ['/mobgenners/update/', 'id' => $mobgenner->id] , ['class' => 'btn btn-primary']) ?>
                            </p>
                            <?php
                            echo DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    'first_name',
                                    'last_name',
                                    'username',
                                    'email:email',
                                    [
                                        'attribute' => 'role_id',
                                        'value' => Utils::getRolById($model->role_id)
                                    ],
                                    [
                                        'attribute' => 'status',
                                        'value' => $model->status == 1 ? 'Active' : 'Disabled'
                                    ],
                                    //'created_at:date',
                                    //'updated_at:date',
                                ],
                            ]);
                            ?>

                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane <?= $pass_tab;?>" id="password">
                            <?= $this->render('changepassword', [
                                'model' => $model,
                                'modelpass'=>$modelpass,
                                'user' => $user
                            ]); ?>
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div>
                <!-- /.nav-tabs-custom -->
            </div>
        </div>
</div>

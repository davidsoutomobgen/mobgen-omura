<?php
use common\models\User;
use backend\models\Builds;
?>
<div class="box box-<?= $bordercolor?>">
    <?php if ($header) { ?>
        <div class="box-header with-border">
            <h3 class="box-title"><?= Yii::t('app', 'Resume profile');?></h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
        </div>
    <?php } ?>
    <div class="box-body box-profile">
        <?php
        $user = User::getUserInfo($model->id);
        ?>
        <img class="profile-user-img img-responsive img-circle" src="<?= $user->image ?>" alt="User profile picture">

        <h3 class="profile-username text-center">
            <?php echo $user->first_name . ' ' . $user->last_name ; ?>
        </h3>

        <p class="text-muted text-center"><?= $user->job_title; ?></p>

        <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
                <b><?= Yii::t('app', 'Projects');?></b>
                <a class="pull-right"><?= Builds::find()->getOtaProjectsByUser($user->id); ?></a>
            </li>
            <li class="list-group-item">
                <b><?= Yii::t('app', 'Builds');?></b> <a class="pull-right"><?= Builds::find()->getBuildsByUser($user->id); ?></a>
            </li>
            <!--
            <li class="list-group-item">
                <b><?= Yii::t('app', 'Groups');?></b> <a class="pull-right">-</a>
            </li>
            -->
        </ul>
    </div>
    <?php if ($header) { ?>
        <div class="box-footer text-center">
            <a href="/user/profile/<?= $user->id;?>" class="uppercase"><?= Yii::t('app', 'See Profile') ?></a>
        </div>
    <?php } ?>
</div>
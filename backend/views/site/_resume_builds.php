<?php
use yii\helpers\Html;

use common\models\User;

$userIdRole = User::getUserIdRole();
?>
<div class="box box-<?=$bordercolor;?>">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $title; ?></h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="table-responsive">
            <table class="table no-margin">
                <thead>
                <tr>
                    <th><?= Yii::t('app', 'Name') ?></th>
                    <th><?= Yii::t('app', 'Hash') ?></th>
                    <th><?= Yii::t('app', 'Type') ?></th>
                    <?php if ($showcreated) { ?>
                        <th><?= Yii::t('app', 'Created') ?></th>
                    <?php } ?>
                    <th><?= Yii::t('app', 'OtaProject') ?></th>
                    <th><?= Yii::t('app', 'Date') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($lastbuilds)) { ?>
                    <?php foreach($lastbuilds as $build) { ?>
                        <?php //print_r($build); die;?>
                        <tr>
                            <!-- <td><?//= $build->buiId; ?></td> -->
                            <td>
                                <?php
                                if (($userIdRole != Yii::$app->params['QA_ROLE']) && ($userIdRole != Yii::$app->params['CLIENT_ROLE']))
                                    echo Html::a($build->buiName,
                                        Yii::$app->params["BACKEND"].'/builds/update/'.$build->buiId,
                                        ['target'=>'_blank', 'title'=>$build->buiName, 'alt'=>$build->buiName]);
                                else
                                    echo Html::a($build->buiName,
                                        Yii::$app->params["BACKEND"].'/builds/view/'.$build->buiId,
                                        ['target'=>'_blank', 'title'=>$build->buiName, 'alt'=>$build->buiName]);
                                ?>
                            </td>
                            <!-- <td><?= $build->buiVisibleClient; ?></td> -->
                            <td>
                                <?php
                                $frontend = Yii::$app->params['FRONTEND'];
                                $path_file = Yii::$app->params["DOWNLOAD_BUILD_DIR"] .  $build->buiFile;

                                if (file_exists($path_file)) {
                                    if ($build->buiVisibleClient == 1)
                                        $link = Html::a($build->buiHash,
                                            Yii::$app->params["FRONTEND"].'/build/'.$build->buiHash.'/'.$build->buiSafename,
                                            ['target'=>'_blank', 'title'=>$build->buiName, 'alt'=>$build->buiName, 'class'=>'green-build']
                                        );
                                    else if ($build->buiVisibleClient == 2)
                                        $link = Html::a($build->buiHash,
                                            Yii::$app->params["BACKEND"].'/build/'.$build->buiHash.'/'.$build->buiSafename,
                                            ['target'=>'_blank', 'title'=>$build->buiName, 'alt'=>$build->buiName, 'class'=>'orange-build']
                                        );
                                    else
                                        $link = Html::a($build->buiHash,
                                            Yii::$app->params["BACKEND"].'/build/'.$build->buiHash.'/'.$build->buiSafename,
                                            ['target'=>'_blank', 'title'=>$build->buiName, 'alt'=>$build->buiName, 'class'=>'red-build']
                                        );
                                }
                                else
                                    $link = '<span class="text">Not available</span>';

                                echo $link;
                                ?>

                            </td>
                            <td>
                                <?php
                                if (empty($build->buiType))
                                    $logo = '/images/apple.png';
                                else
                                    $logo = '/images/android.png';

                                echo Html::img(Yii::getAlias('@web').$logo, ['width'=>'24']);
                                ?>
                            </td>
                            <?php if ($showcreated) { ?>
                                <td><?= $build->createdBy->first_name.' '.$build->createdBy->last_name ?></td>
                            <?php } ?>
                            <td>
                                <?= Html::a($build->buiProIdFK0->name,
                                    Yii::$app->params["BACKEND"].'/otaprojects/'.$build->buiProIdFK,
                                    ['target'=>'_blank', 'title'=>$build->buiProIdFK0->name, 'alt'=>$build->buiProIdFK0->name]); ?>
                            </td>
                            <td><?= date('d-m-Y H:i:s', $build->updated_at); ?></td>
                        </tr>
                    <?php } ?>
                <?php
                }
                else {
                    if ($showcreated)
                        echo '<td colspan="6">' . Yii::t('app', 'Doesn\'t exist builds.') . '</td>';
                    else
                        echo '<td colspan="5">' . Yii::t('app', 'You haven\'t builds.') . '</td>';
                }
                ?>
                </tbody>
            </table>
        </div>
        <!-- /.table-responsive -->
    </div>
    <!-- /.box-body -->
</div>
<?php
use yii\helpers\Html;
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
                        <tr>
                            <!-- <td><?= $build->buiId; ?></td> -->
                            <td>
                                <?= Html::a($build->buiName,
                                    Yii::$app->params["BACKEND"].'/builds/update/'.$build->buiId,
                                    ['target'=>'_blank', 'title'=>$build->buiName, 'alt'=>$build->buiName]); ?>
                            </td>
                            <td>
                                <?php
                                $frontend = Yii::$app->params['FRONTEND'];
                                $path_file = Yii::$app->params["DOWNLOAD_BUILD_DIR"] .  $build->buiFile;

                                if (file_exists($path_file))
                                    $link = Html::a($build->buiHash,
                                        Yii::$app->params["FRONTEND"].'/build/'.$build->buiHash.'/'.$build->buiSafename,
                                        ['target'=>'_blank', 'title'=>$build->buiName, 'alt'=>$build->buiName]
                                    );
                                else
                                    $link = '<span class="text-red">' . $build->buiHash . '</span>';

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
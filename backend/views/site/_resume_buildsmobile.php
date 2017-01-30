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
        <div class="box-body">
            <ul class="products-list product-list-in-box">
                <?php
                if (!empty($lastbuilds)) {
                    foreach($lastbuilds as $build) {

                    ?>
                    <li class="item">
                        <ul>
                            <li>
                                <b><?= Yii::t('app', 'Build name: '); ?></b>
                                <?=$build->buiName;

                                /*= Html::a($build->buiName,
                                Yii::$app->params["BACKEND"].'/builds/update/'.$build->buiId,
                                ['target'=>'_blank', 'title'=>$build->buiName, 'alt'=>$build->buiName]);
                                */
                                ?>
                            </li>

                            <li>
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
                            </li>
                            <li>
                                <?php
                                if (empty($build->buiType))
                                    $logo = '/images/apple.png';
                                else
                                    $logo = '/images/android.png';

                                echo Html::img(Yii::getAlias('@web').$logo, ['width'=>'24']);
                                ?>
                            </li>
                            <?php if ($showcreated) { ?>
                                <li>
                                    <b><?= Yii::t('app', 'Created by: '); ?> </b>
                                    <?= $build->createdBy->first_name.' '.$build->createdBy->last_name ?>
                                </li>
                            <?php } ?>
                            <li>
                                <b><?= Yii::t('app', 'OtaProject: '); ?></b>
                                <?= Html::a($build->buiProIdFK0->name,
                                    Yii::$app->params["BACKEND"].'/otaprojects/'.$build->buiProIdFK,
                                    [ 'title'=>$build->buiProIdFK0->name, 'alt'=>$build->buiProIdFK0->name]);
                                ?>
                            </li>




                            <span class="product-description">
                                <?php echo date('d-m-Y', $build->updated_at); ?>
                            </span>
                        </ul>
                    </li>
                    <?php } ?>
                <?php } ?>
            </ul>
        </div>
    </div>
    <!-- /.box-body -->
</div>
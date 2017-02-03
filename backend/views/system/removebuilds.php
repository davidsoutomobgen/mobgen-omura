
<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use backend\models\Builds;

/* @var $this yii\web\View */
/* @var $model backend\models\System */

$this->title = Yii::t('app', 'Remove Old Builds');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Systems'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Remove Builds');
?>
<div class="system-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(['id' => 'removeForm']); ?>
    <div class="btn-header">
        <p id="submit_form" >
            <?= Html::submitButton(Yii::t('app', 'Remove Builds'), ['class' => 'btn btn-danger']) ?>
            <?= Html::a( Yii::t('app', 'Back'), Yii::$app->request->referrer, ['class' => 'btn btn-warning']);?>
        </p>
    </div>
    <div class="clear"></div>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo Yii::t('app', 'Choose the option');?></h3>
        </div>
        <div class="col-xs-12">
            <div class=" callout callout-danger uploadfirst">
                <h4>Warning!</h4>
                <p class="alignleft"><?= Yii::t('app', 'This action is not undone. The builds ') ?></p>
                <p class="alignleft"><?= Yii::t('app', 'The builds selected as Favorites can not remove with this method.') ?></p>
            </div>
        </div>
        <?php
        $i = 3;
        $date = date("d.m.Y");
        for ($i=3; $i >0; $i--) {
        ?>
            <div class="col-xs-4">
                <div class="radio">
                    <label>
                        <input type="radio" name="optionsRadios" id="optionsRadios1" value="<?= $i ?>" />
                        <?= $i ?> months old
                        <?php
                            $date_remove = strtotime( $date . ' - ' . $i . ' month');
                            echo '[' . date('d.m.Y', $date_remove). ' - ';
                            echo 'Delete <span class="text-red">'. Builds::find()->countRemoveBuilds($date_remove) . '</span>' . Yii::t('app', ' builds'). ']';
                        ?>
                        <input type="hidden" name="date_<?= $i ?>" id="date_<?= $i ?>" value="<?= $date_remove ?>" />
                    </label>
                </div>
            </div>
        <?php } ?>
        <div class="clear"></div>
    </div>
    <div class="btn-footer">
        <p id="submit_form" >
            <a class="btn btn-danger" href="/otaprojects/delete/2731" data-confirm="Are you sure you want to delete this item?" data-method="post">Delete</a>
            <?= Html::submitButton(Yii::t('app', 'Remove Builds'), ['class' => 'btn btn-danger']) ?>
            <?= Html::a( Yii::t('app', 'Back'), Yii::$app->request->referrer, ['class' => 'btn btn-warning']);?>
        </p>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<script type="text/javascript">
    document.getElementById('removeForm').onsubmit = function(){
        return confirm('Do you really want to remove old builds?');
    };
</script>

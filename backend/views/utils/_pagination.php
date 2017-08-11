<?php
use yii\widgets\ActiveForm;
?>
<div id="pagesize">
    <?php $form = ActiveForm::begin(); ?>
        <?php
            $searchModel->pagesize = $dataProvider->pagination->pagesize;
            echo $form->field($searchModel, 'pagesize')
                ->dropDownList(
                    \Yii::$app->params['ARRAY_PAGESIZE']
            )->label('');
        ?>
        <div id="textpagesize"><p><?php echo Yii::t('app', 'elements by page');?></p></div>
    <?php ActiveForm::end(); ?>
</div>
<div class="clear"></div>
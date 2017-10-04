<?php
use yii\base\Component;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\widgets\Pjax;
use backend\models\Permissions;
use backend\models\PermissionsGroups;

$permissions = Permissions::find()->all(); //->where('type = 2');

//
//echo '<pre>';print_r($node); echo '</pre>';die;
//echo '<pre>';print_r($params['node']->id); echo '</pre>'; //die;
//echo '<pre>';print_r($permissions); echo '</pre>'; die;
foreach ($permissions as $permission) {
    $data[$permission['id']] =  $permission['label'];
}

//echo '<pre>';print_r($params['node']->root); echo '</pre>'; //die;
$parent = array();
$maxlevel = $node->lvl;

if ($node->lvl > 0) {
    $level = $node->lvl;
    do {
        $parent[]  = Permissions::find()->obtainInheritPermissions($node->root, $level, $node->rgt, $node->lft);
        $level = $level - 1;
    }while ($level > 0);

    //echo '<pre>';print_r($parent); echo '</pre>'; //die;
}

$selected = PermissionsGroups::find()->with('idPermissions')->with('idGroup')->where('id_group = :idgroup',  [':idgroup' => $node->id])->all();
if (!empty ($selected)) {
    foreach ($selected as $sel) {

        $value[] = (int)$sel->id_permission;
    }
}
else $value = array();
//echo '<pre>';print_r($parent); echo '</pre>'; //die;
?>
<div class="permissions-group-admin">
    <h3><?= Yii::t('app', 'Admin Permissions'); ?></h3>
    <?= Html::beginForm(['/groups'], 'post', ['data-pjax' => '', 'class' => 'form-inline', 'id' => 'permissions-groups'] ); ?>
    <div class="form-group">
        <?php
        echo Select2::widget([
            'name' => 'permissions',
            'value' => $value,
            'data' => $data,
            'size' => Select2::LARGE,
            'options' => ['placeholder' => 'Select  permission ...', 'multiple' => true],
            'pluginOptions' => [
                'allowClear' => true,
                'tags' => true,
            ],
        ]);
        ?>
    </div>
    <br /><br />
    <div class="form-group">
        <?= Html::hiddenInput('id_group',$node->id); ?>
        <?= Html::submitButton(Yii::t('app', 'Save Permissions'), ['class' => 'btn btn-primary', 'name' => 'save-permission-button']) ?>
    </div>
    <?= Html::endForm() ?>

    <div>
        <?php if ($maxlevel > 0) { ?>
            <h3><?= Yii::t('app', 'Inherited Permissions'); ?></h3>
            <?php if (count($parent) > 0) { ?>
                <ul>
                    <?php
                    foreach ($parent as $par) {
                        foreach ($par as $sel) {
                            echo "<li>{$sel['label']}</li>";
                        }
                    }
                    ?>
                </ul>

                <?php
            } else echo "<p>This group hasn't inherited permissions.</p>";
        }
        ?>
        <h3><?= Yii::t('app', 'Permissions in this Group'); ?></h3>
        <?php if(!empty($selected)) { ?>
            <ul>
                <?php
                foreach ($selected as $sel) {
                    echo '<li>'.$sel->idPermissions->label.'</li>';
                }
                ?>
            </ul>

        <?php
        }
        else echo "<p>This group hasn't permissions selected</p>";
        ?>
    </div>

    <?php //Pjax::begin(['enablePushState' => false]); ?>
    <!-- <h1><? ?></h1> ->
    <!--
    <?= Html::a('', ['/groups'], ['class' => 'btn btn-lg btn-warning glyphicon glyphicon-arrow-up']) ?>
    <?= Html::a('', ['/groups'], ['class' => 'btn btn-lg btn-primary glyphicon glyphicon-arrow-down']) ?>
    -->
    <?php //Pjax::end(); ?>
</div>
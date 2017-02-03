<?php
use kartik\growl\Growl;

if (Yii::$app->session->hasFlash('create')) {
    echo Growl::widget([
        'type' => Growl::TYPE_INFO,
        'title' => '', //Yii::t('app', 'Ok!'),
        'icon' => 'glyphicon glyphicon-ok-sign',
        'body' => Yii::t('app', 'Elemento creado correctamente.'),
        'showSeparator' => false,
        'delay' => 0,
        'pluginOptions' => [
            'showProgressbar' => false,
            'placement' => [
                'from' => 'top',
                'align' => 'right',
            ]
        ]
    ]);
}
else if (Yii::$app->session->hasFlash('update')) {
    echo Growl::widget([
        'type' => Growl::TYPE_INFO,
        'title' => '', //Yii::t('app', 'Ok!'),
        'icon' => 'glyphicon glyphicon-ok-sign',
        'body' => Yii::t('app', 'Elemento actualizado correctamente.'),
        'showSeparator' => false,
        'delay' => 0,
        'pluginOptions' => [
            'showProgressbar' => false,
            'placement' => [
                'from' => 'top',
                'align' => 'right',
            ]
        ]
    ]);
}
else if (Yii::$app->session->hasFlash('remove')) {
        echo '<div class="alert alert-success alert-dismissible">';
        echo '<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>';
        echo '<h4><i class="icon fa fa-check"></i>Project deleted!</h4>';
        //echo '<p class="alignleft">Lorem ipsum...</p>';
        echo '</div>';
}
/*
 *
         <div class="alert alert-success alert-dismissible">
          <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
          <h4><i class="icon fa fa-check"></i>Project deleted!</h4>
          <!-- <p class="alignleft">Lorem ipsum...</p> -->
        </div>

 *
 */
?>



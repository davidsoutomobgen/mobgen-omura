<?php 
namespace backend\models;

use Yii;
use common\models\User;


class PasswordForm extends \common\models\CActiveRecord{
    public $oldpass;
    public $newpass;
    public $repeatnewpass;
    
    public function rules(){
        return [
            [['oldpass', 'newpass', 'repeatnewpass'],'required'],
            ['oldpass','findPasswords'],
            ['repeatnewpass','compare','compareAttribute'=>'newpass'],
        ];
    }
    
    public function findPasswords($attribute, $params){
        $user = User::find()->where([
            'username'=>Yii::$app->user->identity->username
        ])->one();

        $hash = Yii::$app->getSecurity()->generatePasswordHash($this->oldpass);
//echo $hash.' -- '.$this->oldpass.'<br />';
        if (!$user->validatePassword($this->oldpass))
            $this->addError($attribute, Yii::t('app', 'Old password is incorrect'));
    }
    
    public function attributeLabels(){
        return [
            'oldpass'=>'Old Password',
            'newpass'=>'New Password',
            'repeatnewpass'=>'Repeat New Password',
        ];
    }
}
?>
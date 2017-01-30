<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\User;


/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'There is no user with such email.'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);
        //echo '<pre>'; print_r($user->email); echo '</pre>'; die;

        if ($user) {
            if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
                $user->generatePasswordResetToken();
            }

            if ($user->save()) {
                $sendEmail = Yii::$app->mailer->compose('passwordResetToken', ['user' => $user])
                //$sendEmail = Yii::$app->mailer->compose()
                    //->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
                    ->setFrom(['otashare@mobgen.com' => 'OTAShare - MOBGEN'])
                    ->setTo($user->email)
                    ->setSubject('Password reset for ' . Yii::$app->name)
                    ->send();

                //var_dump($sendEmail);
                /*
                echo '<br>dddd<br>';
                $sendTo = 'david.souto@mobgen.com';
                $mail = 'casa';
                $subject = 'dddd';

                $sendEmail2 = Yii::$app->mailer->compose()
                    ->setFrom(['otashare@mobgen.com' => 'OTAShare - MOBGEN'])
                    ->setTo($sendTo)
                    ->setSubject($subject)
                    ->setHtmlBody($mail)
                    ->send();

                var_dump($sendEmail2);

                Builds::_SendMail($sendTo, $template, $domain, $project, $build, $user->id);
                */
                //die;

                //mail('davidsoutoc@gmail.com', 'Test Email', 'Test Message body');

                return $sendEmail;
            }
        }
        else {
            echo 'allo'; die;
        }

        return false;
    }
}

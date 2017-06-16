<?php
namespace backend\models;

//use backend\models\AuthAssignment;
use yii\base\Model;
use common\models\User;
use backend\models\Permissions;
use backend\models\PermissionsUsers;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $first_name;
    public $last_name;
    public $username;
    public $email;
    public $status;
    public $password;
    public $permissions;
    public $role_id;
    public $sendEmail;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username','required'],

            ['first_name', 'required','message'=>'have to fill this field'],

            ['last_name', 'required'],
            ['status', 'required'],

            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['role_id', 'integer'],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            //[['email', 'role_id'], 'unique', 'targetAttribute' => ['email', 'role_id'], 'message' => 'DDDD'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->first_name = $this->first_name;
            $user->last_name = $this->last_name;
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
	        $user->role_id = $this->role_id;
            $user->save();

            // lets add the permissions
            $permissionList = new Permissions;
            $permissionList = $permissionList->find()->all();
            //print_r($permissionList);die;
            foreach ($permissionList as $value)
            {
                //print_r($value->id);die;
                $newPermission = new PermissionsUsers;
                $newPermission->id_permission = $value->id;
                $newPermission->id_user = $user->id;
                $newPermission->state = 1;
                $newPermission->save();
            }

            return $user;
        }
        
        return null;
    }
}

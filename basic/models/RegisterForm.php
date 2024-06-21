<?php

namespace app\models;

use Yii;
use yii\base\Model;

class RegisterForm extends Model
{
    public $fio;
    public $username;
    public $email;
    public $password;
    public $status = 1;
    public $role = 1;

    public function rules()
    {
        return [
            [['fio', 'username', 'email', 'password', 'status', 'role'], 'required'],
            ['email', 'email'],
            [['fio', 'username', 'email', 'password'], 'string', 'max' => 255],
        ];
    }

    public function register()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->fio = $this->fio;
        $user->username = $this->username;
        $user->email = $this->email;
        $user->password = $this->password;
        $user->status = 1;
        $user->role = 1;

        return $user->save() ? $user : null;
    }
}
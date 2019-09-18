<?php
namespace micro\models;

use Yii;
use yii\base\Model;

class LoginForm extends Model
    {
    public $email;
    public $password;

    public function rules()
        {
        return [
            [['email', 'password'], 'required'],
            ['email', 'email'],
//            ['rememberMe', 'boolean'],
//            ['password', 'validatePassword'],
        ];
        }

    public function attributeLabels()
        {
        return [
            'rememberMe'    => 'Запомнить',
            'email'         => 'E-mail',
            'password'      => 'Пароль'
            ];
        }        
    }

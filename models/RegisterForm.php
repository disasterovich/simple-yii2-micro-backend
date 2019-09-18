<?php
namespace app\models;

use Yii;
use yii\base\Model;

class RegisterForm extends Model
    {
    public $name;
    public $email;
    public $password;
    public $password2;

    public function rules()
        {
        return [
            ['name', 'required'],
            [['email', 'password', 'password2'], 'required'],
            ['email', 'email'],
            [ 'password2', 'compare', 'compareAttribute' => 'password' ],
            [ ['password', 'password2'], 'string', 'length' => [6,40] ],
            [ 'name', 'string', 'max' => [50] ],
            [ 'email','filter','filter'=>'strtolower' ],
        ];
        }

    public function attributeLabels()
        {
        return [
            'name' => 'Имя',
            'rememberMe' => 'Запомнить',
            'email' => 'E-mail',
            'password' => 'Пароль',
            'password2' => 'Повтор пароля',
            ];
        }        
    }

<?php
namespace micro\models;

use Yii;

class RegisterForm extends \yii\base\Model
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

    public function register()
    {
        if ( !User::find()->where(['email' => $this->email])->exists() ) {
            $user = new User();
            $user->name = $this->name;
            $user->email = $this->email;
            return $user->save(false);
        }

        $this->addError('email', 'Пользователь с таким email уже есть в базе');
        return false;
    }

    }

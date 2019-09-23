<?php
namespace micro\models;

use Yii;
use yii\base\Model;

class LoginForm extends Model
    {
    public $email;
    public $password;

    protected $_userData;

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

    public function getUserData()
    {
        return $this->_userData;
    }

    public function login()
    {
        $user = User::findOne(['email' => $this->email]);

        if ($user) {
            if ($user->status == User::STATUS_BLOCKED) {
                $this->addError('email','Ваш аккаунт блокирован, свяжитесь с администратором');
            }
            elseif ($user->status == User::STATUS_INACTIVE && $user->activation_key != '') {
                $this->addError('email','Ваш аккаунт не активирован. Для активации перейдите по ссылке указанной в регистрационном письме. Если Вы не получили письмо, свяжитесь с нами.');
            }
            else {
                $password_hash = User::hashPassword($this->password, $user->salt);

                if ($password_hash === $user->password) {

                    $this->_userData = [
                        'token' => $user->access_token,
                        'id'    => $user->id,
                        'email' => $user->email,
                        'name'  => $user->name,
                    ];

                    return true;
                }
                else {
                    $this->addError('password', 'Неверный пароль');
                }
            }
        }
        else {
            $this->addError('email', 'Пользователь не найден');
        }

        return false;
    }

    }

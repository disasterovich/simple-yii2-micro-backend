<?php

namespace micro\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
    {
    const STATUS_INACTIVE=0; //пользователь еще не активировал свой аккаунт
    const STATUS_ACTIVE=1; //пользователь активен
    const STATUS_BLOCKED=2; //пользователь блокирован админом

    public static function tableName()
    {
        return '{{users}}';
    }

    public function getId()
        {
        return $this->id;
        }

    public function validateAuthKey($authKey)
        {
        }

    public static function findIdentityByAccessToken($token, $type = null)
        {
        return static::findOne(['access_token' => $token]);
        }

    public static function findIdentity($id)
        {
        }

    public function getAuthKey()
        {
        }

        // Создание хэша пароля
    public static function hashPassword($password,$salt)
        {
        return md5($salt.$password);
        }

    public function beforeSave($insert)
    {
        if($this->isNewRecord) {
            $this->date = time();
            $this->salt = self::randomSalt();
            $this->status = self::STATUS_INACTIVE;
            $this->activation_key = substr(md5(uniqid(rand(), true)), 0, 20);
            $this->access_token = uniqid();
            $this->password = self::hashPassword($this->password, $this->salt);
        }

        return parent::beforeSave($insert);
    }

    /**
     * Генерация "соли". Этот метод генерирует случайным образом слово
     * заданной длины. Длина указывается в единственном свойстве метода.
     * Символы, применяемые при генерации, указаны в переменной $chars.
     * По умолчанию, длина соли 32 символа.
     * @param int $length
     * @return string
     */
    public static function randomSalt($length=32)
    {
        $chars = 'abcdefghijkmnopqrstuvwxyz023456789';
        srand((double)microtime()*1000000);
        $i = 1;
        $salt = '' ;

        while ($i <= $length) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $salt .= $tmp;
            $i++;
        }
        return $salt;
    }

    }
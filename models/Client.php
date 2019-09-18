<?php

namespace micro\models;

use yii;
use yii\db\ActiveRecord;

class Client extends ActiveRecord
{
    public static function tableName()
    {
        return '{{clients}}';
    }

}
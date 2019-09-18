<?php

namespace micro\models;

use yii;
use yii\db\ActiveRecord;

class Departure extends ActiveRecord
{
    public static function tableName()
    {
        return '{{departures}}';
    }

}
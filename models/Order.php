<?php

namespace micro\models;

use yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Order extends ActiveRecord
{
    protected $client_name;
    protected $client_phone;
    protected $departure_address;
    protected $departure_from;
    protected $departure_to;

    public static function tableName()
    {
        return '{{orders}}';
    }

    public function fields()
    {
        return array_merge(parent::fields(), [
            'client_name',
            'client_phone',
            'departure_address',
            'departure_from',
            'departure_to',
        ]);
    }

    public function behaviors ()
    {
        return [
            [
                'class'              => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
            ]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::class, ['id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeparture()
    {
        return $this->hasOne(Departure::class, ['id' => 'departure_id']);
    }

}
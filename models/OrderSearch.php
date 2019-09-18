<?php

namespace micro\models;

use yii\data\ActiveDataProvider;
use micro\models\Order;

class OrderSearch extends Order
{

    public function rules()
        {
        return [
              [['client_name'], 'safe'],
            ];
        }

    public function search($params)
        {
        $query = Order::find()
            ->alias('t')
            ->select('
                t.id, 
                t.created_at, 
                clients.name as client_name, 
                clients.phone as client_phone,
                departures.address as departure_address, 
                departures.from as departure_from, 
                departures.to as departure_to
            ')
            ->joinWith(['client', 'departure']);

        $dataProvider = new ActiveDataProvider(['query' => $query]);

        $dataProvider->sort->attributes['client_name'] = [
            'asc'     => ['clients.name' => SORT_ASC],
            'desc'    => ['clients.name' => SORT_DESC],
        ];

        $this->load($params,'');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
            }

        $query->andFilterWhere([
           'like' , 'clients.name', $this->client_name
        ]);

        return $dataProvider;
        }
}
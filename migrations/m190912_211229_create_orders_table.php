<?php

use yii\db\Migration;

/**
 * Handles the creation of table `orders`.
 */
class m190912_211229_create_orders_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('orders', [
            'id' => $this->primaryKey(),
            'client_id' => $this->integer(),
            'departure_id' => $this->integer(),
            'created_at' => $this->integer()->unsigned(),
            'updated_at' => $this->integer()->unsigned(),
        ]);

        $this->addForeignKey('fk_orders_client_id','orders','client_id','clients','id','CASCADE','CASCADE');
        $this->addForeignKey('fk_orders_departure_id','orders','departure_id','departures','id','CASCADE','CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('orders');
    }
}

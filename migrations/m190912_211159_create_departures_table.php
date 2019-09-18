<?php

use yii\db\Migration;

/**
 * Handles the creation of table `departures`.
 */
class m190912_211159_create_departures_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('departures', [
            'id' => $this->primaryKey(),
            'address' => $this->string(200),
            'from' => $this->integer()->unsigned(),
            'to' => $this->integer()->unsigned(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('departures');
    }
}

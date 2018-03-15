<?php

use yii\db\Migration;

/**
 * Handles the creation of table `delivery`.
 */
class m180314_062702_create_delivery_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('delivery', [
            'delivery_id' => $this->primaryKey(),
//            delivery_name	varchar	配送方式名称
            'delivery_name' => $this->string()->notNull()->comment('配送方式名称'),
//delivery_price	float	配送方式价格
            'delivery_price' => $this->decimal()->notNull()->comment('配送方式价格'),

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('delivery');
    }
}

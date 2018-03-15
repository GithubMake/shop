<?php

use yii\db\Migration;

/**
 * Handles the creation of table `payment`.
 */
class m180314_063033_create_payment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('payment', [
            'payment_id' => $this->primaryKey(),
//            payment_id	int	支付方式id
//payment_name	varchar	支付方式名称
            'payment_name' => $this->string()->notNull()->comment('支付方式名称'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('payment');
    }
}

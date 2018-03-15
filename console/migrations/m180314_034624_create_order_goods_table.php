<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order_goods`.
 */
class m180314_034624_create_order_goods_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('order_goods', [
            'id' => $this->primaryKey(),
//            order_id	int	订单id
            'order_id' => $this->integer()->notNull()->comment('订单id'),
//goods_id	int	商品id
            'goods_id' => $this->integer()->notNull()->comment('商品id'),
//goods_name	varchar(255)	商品名称
            'goods_name' => $this->string()->notNull()->comment('商品名称'),
//logo	varchar(255)	图片
            'logo' => $this->string()->notNull()->comment('图片'),
//price	decimal	价格
            'price' => $this->decimal()->notNull()->comment('价格'),
//amount	int	数量
            'amount' => $this->integer()->notNull()->comment('数量'),
//total	decimal	小计
            'total' => $this->decimal()->notNull()->comment('小计'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('order_goods');
    }
}

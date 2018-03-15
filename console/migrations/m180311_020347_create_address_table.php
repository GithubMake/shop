<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m180311_020347_create_address_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'username'=>$this->string(50)->notNull()->comment('用户名'),
            'province'=>$this->string(10)->notNull()->comment('省'),
            'city'=>$this->string(10)->notNull()->comment('市'),
            'area'=>$this->string(10)->notNull()->comment('县'),
            'detail_address'=>$this->string(100)->notNull()->comment('详细地址'),
            'tel'=>$this->string(20)->notNull()->comment('电话'),
            'member_id'=>$this->string(3)->notNull()->comment('会员'),
            'status'=>$this->string(2)->notNull()->defaultValue(0)->comment('状态'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('address');
    }
}

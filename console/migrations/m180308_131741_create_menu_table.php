<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m180308_131741_create_menu_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'label'=>$this->string(50)->comment('标签'),
            'url'=>$this->string(50)->comment('路由'),
            'parent_id'=>$this->integer(4)->comment('父id'),
            'sort'=>$this->integer(4)->defaultValue(0)->comment('排序'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('menu');
    }
}

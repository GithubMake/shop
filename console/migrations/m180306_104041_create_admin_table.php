<?php

use yii\db\Migration;

/**
 * Handles the creation of table `admin`.
 */
class m180306_104041_create_admin_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('admin', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique()->comment('用户名'),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull()->comment('密码'),
            'password_reset_token' => $this->string()->defaultValue(0)->comment('重置密码'),
            'email' => $this->string()->notNull()->unique()->comment('邮箱'),

            'status' => $this->smallInteger()->notNull()->defaultValue(10)->comment('状态'),
            'created_at' => $this->integer()->notNull()->comment('创建时间'),
            'updated_at' => $this->integer()->notNull()->comment('更新时间'),
            'last_login_time'=>$this->string()->notNull()->comment('最后登录时间'),
            'last_login_ip'=>$this->string()->notNull()->comment('最后登录ip')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('admin');
    }
}

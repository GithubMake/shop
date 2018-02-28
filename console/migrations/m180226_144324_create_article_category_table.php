<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article-category`.
 */
class m180226_144324_create_article_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article-category', [
            'id' => $this->primaryKey(),
//            name	varchar(50)	名称
            'name'=>$this->string(50)->notNull()->comment('名称'),
//intro	text	简介
            'intro'=>$this->text()->comment('简介'),
//sort	int(11)	排序
            'sort'=>$this->integer()->notNull()->comment('排序'),
//is_deleted	int(1)	状态(0正常 1删除)
            'is_deleted'=>$this->string(1)->notNull()->comment('状态'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article-category');
    }
}

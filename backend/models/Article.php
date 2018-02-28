<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "article".
 *
 * @property int $id
 * @property string $name 名称
 * @property string $intro 简介
 * @property int $article_category_id 文章分类
 * @property int $sort 排行
 * @property int $is_deleted 状态
 * @property int $create_time 创建时间
 */
class Article extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'article_category_id', 'sort' ], 'required'],
            [['intro'], 'string'],
            [['article_category_id', 'sort', 'is_deleted', 'create_time'], 'integer'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'intro' => '简介',
            'article_category_id' => '文章分类id',
            'sort' => '排行',
            'is_deleted' => '状态',
        ];
    }
}

<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "article_detail".
 *
 * @property int $article_id
 * @property string $content 内容
 */
class ArticleDetail extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'article_id' => '文章主键',
            'content' => '内容',
        ];
    }
}

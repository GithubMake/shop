<?php

namespace backend\models;


use yii\db\ActiveRecord;

/**
 * This is the model class for table "goods_day_count".
 *
 * @property string $day 日期
 * @property int $count 商品数
 */
class GoodsDayCount extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_day_count';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['count'], 'integer'],
            [['day'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'day' => '日期',
            'count' => '商品数',
        ];
    }
}

<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property int $id
 * @property string $label 标签
 * @property string $url 路由
 * @property int $parent_id 父id
 * @property int $sort 排序
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['label','parent_id','url','sort'],'required'],
            [['parent_id', 'sort'], 'integer'],
            [['label', 'url'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'label' => '标签',
            'url' => '路由',
            'parent_id' => '上级菜单',
            'sort' => '排序',
        ];
    }

    public static function getParentIdList()
    {
        $parentIdList = [];
        $parentIds = self::find()->where(['parent_id'=>0])->all();

        foreach ($parentIds as $parentId){
            $parentIdList[$parentId->id]=$parentId->label;
        }
        return $parentIdList;
    }


}

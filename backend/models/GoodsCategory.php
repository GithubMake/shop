<?php

namespace backend\models;

use yii\db\ActiveRecord;
use creocoder\nestedsets\NestedSetsBehavior;

/**
 * This is the model class for table "goods_category".
 *
 * @property int $id
 * @property int $tree 树id
 * @property int $lft 左值
 * @property int $rgt 右值
 * @property int $depth 层级
 * @property string $name 名称
 * @property int $parent_id 上层分类id
 * @property string $intro 简介
 */
class GoodsCategory extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tree', 'lft', 'rgt', 'depth', 'parent_id'], 'integer'],
            [['name', 'parent_id'], 'required'],
            [['intro'], 'string'],
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
            'tree' => '树id',
            'lft' => '左值',
            'rgt' => '右值',
            'depth' => '层级',
            'name' => '名称',
            'parent_id' => '上层分类id',
            'intro' => '简介',
        ];
    }

//>>>>>>>>>>>>>>>>>>>>>>以下代码用于creocoder插件 begin  >>>>>>>>>>>>>>>>>>>>>>>>>>
    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                 'treeAttribute' => 'tree',
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new GoodsCategoryQuery(get_called_class());
    }
//>>>>>>>>>>>>>>>>>>>>>>>>>>>    end    >>>>>>>>>>>>>>>>>>>>
    public function testDeleteWithChildren()
    {
        $this->assertEquals(7, Tree::findOne(9)->deleteWithChildren());
        $this->assertEquals(7, MultipleTree::findOne(31)->deleteWithChildren());
        $dataSet = $this->getConnection()->createDataSet(['tree', 'multiple_tree']);
        $expectedDataSet = $this->createFlatXMLDataSet(__DIR__ . '/data/test-delete-with-children.xml');
        $this->assertDataSetsEqual($expectedDataSet, $dataSet);
    }
}

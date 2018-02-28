<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

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

    /**
     * 用于关联查询,(asArray)性能优化,将对象转化为数组处理,下面调用方法从
     *  $items[$articleCategory->id]= $articleCategory->name;转化为
     *  $items[$articleCategory['id']]= $articleCategory['name'];
     * @return array
     */
    public static function getItems(){
         $articleCategories = ArticleCategory::find()->asArray()->all();//查询出文章分类的所有数据
//         $items=[];//用于保存下拉框的数据
//         foreach ($articleCategories as $articleCategory){
//             $items[$articleCategory['id']]= $articleCategory['name'];
//         }
        // return $items;
        return  ArrayHelper::map($articleCategories,'id','name');
    }

    /**
     * 获取分类名字的方法3
     * @return mixed
     */
    public function getName(){
        $articleCategories = ArticleCategory::findOne(['id'=>$this->article_category_id]);
        return $articleCategories->name;
    }

/*    public function getCategory(){
       return  $this->hasOne(ArticleCategory::className(),['article_category_id'=>'id']);
    }*/
}

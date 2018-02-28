<?php
namespace backend\models;//命名空间很重要

use yii\db\ActiveRecord;

class ArticleCategory extends ActiveRecord{
    /**
     * 属性标签修改为中文
     * @return array
     */
    public  function attributeLabels()
    {
        return [
            'name'=>'名字',
            'intro'=>'简介',
            'sort'=>'排行',
        ];
    }
    public function rules()
    {
        return [
            [['name','sort','intro'],'required'],//不为空
            [['sort'],'integer'],//整数
        ];
    }

    /**
     * 自定义一个通过传入id值就可以得到name的静态方法,用于文章表的主页显示分类用
     * @param $id
     * @return mixed
     */
    public static function getNameById($id){
        $articleCategory = self::find()->where(['id'=>$id])->one();
        return $articleCategory->name;
    }
}
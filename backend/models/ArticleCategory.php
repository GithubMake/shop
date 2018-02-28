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
}
<?php
/**
 * Created by PhpStorm.
 * User: asus-pc
 * Date: 2018/2/26
 * Time: 22:52
 */
class ArticleCategory extends \yii\db\ActiveRecord{
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
            [['name','sort'],'required'],//不为空

        ];
    }
}
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


    /**
     * 用于获取parent_id的所有值
     * @return array
     */
    public static function getParentIdList()
    {
        $parentIdList = [];
        $parentIds = self::find()->where(['parent_id'=>0])->all();

        foreach ($parentIds as $parentId){
            $parentIdList[$parentId->id]=$parentId->label;
        }
        return $parentIdList;
    }


    public static function getMenus($menuItems){

        $menuFirsts = self::find()->where(['parent_id'=>0])->all();//获取一级菜单
        foreach ($menuFirsts as $menuFirst){
            $items =[];
            $menuSeconds = self::find()->where(['parent_id'=>$menuFirst->id])->all();//获取二级菜单
            foreach ($menuSeconds as $menuSecond){
                //if(Yii::$app->user->can($menuSecond->url)){//根据用户权限显示二级菜单
                    $items[] = ['label'=>$menuSecond->label,'url'=>[$menuSecond->url]];
                //}
            }
            if($items){//只显示有子菜单的一级菜单
                $menuItems[] =[ 'label' => $menuFirst->label,  'items' =>$items];
            }

        }
        return  $menuItems;
    }


    /**
     * 用于menu的index页面的父分类显示
     * @param $parentId
     * @return mixed
     */
    public static function  getParentIdName($parentId){
         $parent = self::find()->where(['id'=>$parentId])->one();

         return $parent['label'];
    }
}

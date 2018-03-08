<?php
/**
 * Created by PhpStorm.
 * User: asus-pc
 * Date: 2018/3/7
 * Time: 15:33
 */

namespace  backend\models;

use yii\base\Model;

class RoleAdd extends Model{

    public $name;
    public $description;
    public $permission;



    const SCENARIO_ADD = 'add';
    const SCENARIO_EDIT = 'edit';


    public function attributeLabels()
    {
        return [
            'name'=>'角色',
            'description'=>'描述',
            'permission'=>'权限',
        ];
    }


    public function rules()
    {
        return [
            [['name','description'],'required'],
            ['name','validateName','on'=>self::SCENARIO_ADD],//添加路由验证
            ['name','validateEdit','on'=>self::SCENARIO_EDIT],//修改路由验证
            [['permission'],'safe']
        ];
    }


    public function validateName(){
        $authManager = \Yii::$app->authManager;
        if($authManager->getPermission($this->name)){
            $this->addError('name','该角色已存在');//方法返回空值,所有不用返回
        }
    }


    public function validateEdit(){
        if($this->name != \Yii::$app->request->get('name')){//修改了路由
            $this->validateName();
        }

    }


    /**
     * 获取权限,用于角色表的添加选项展示
     * @return array
     */
    public static function getPermissionName(){
        $authManager = \Yii::$app->authManager;
        $permissions = $authManager->getPermissions();
        $items=[];
        foreach ($permissions as $permission){
            $items[$permission->name] = $permission->description.'['.$permission->name.']';
        }//不可以在foreach中直接返回数据,直接返回数据的话,循环一次就退出循环,导致只有一个值
        return $items;
    }


}
<?php
/**
 * Created by PhpStorm.
 * User: asus-pc
 * Date: 2018/3/7
 * Time: 15:33
 */

namespace  backend\models;

use yii\base\Model;

class AddPermission extends Model{

    public $name;
    public $description;

    const SCENARIO_ADD = 'add';
    const SCENARIO_EDIT = 'edit';


    public function attributeLabels()
    {
        return [
            'name'=>'路由',
            'description'=>'描述',
        ];
    }


    public function rules()
    {
       return [
           [['name','description'],'required'],
           ['name','validateName','on'=>self::SCENARIO_ADD],//添加路由验证
           ['name','validateEdit','on'=>self::SCENARIO_EDIT],//修改路由验证
       ];
    }


    public function validateName(){
        $authManager = \Yii::$app->authManager;
        if($authManager->getPermission($this->name)){
            $this->addError('name','该权限已存在');//方法返回空值,所有不用返回
        }
    }


    public function validateEdit(){
        if($this->name != \Yii::$app->request->get('name')){//修改了路由
            $this->validateName();
        }

    }
}
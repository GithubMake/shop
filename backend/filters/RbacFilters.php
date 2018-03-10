<?php
/**
 * Created by PhpStorm.
 * User: asus-pc
 * Date: 2018/3/7
 * Time: 14:37
 */

namespace backend\filters;


use yii\base\ActionFilter;
use yii\web\HttpException;

class RbacFilters extends ActionFilter{

    //控制器动作执行之前
    public  function beforeAction($action)
    {

        if(!\Yii::$app->user->can($action->uniqueId)){//判断是否有权限

            if(\Yii::$app->user->isGuest){//判断登录没
                return  $action->controller->redirect(\Yii::$app->user->loginUrl)->send();//跳转到登录页面,注意一定要添加send
            }
            throw new HttpException(403,'您没有该操作权限');//没有权限抛出异常
        }
        return true;//有权限放行
    }

}
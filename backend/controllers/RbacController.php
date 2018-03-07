<?php

namespace backend\controllers;

use backend\models\AddPermission;
use yii\web\Controller;
use yii\web\HttpException;

class RbacController extends  Controller{

    /**
     * 权限列表
     * @return string
     */
    public function actionPermissionIndex()
    {
        $authManager = \Yii::$app->authManager;
        $permissions = $authManager->getPermissions();
        return $this->render('permission-index',['permissions'=>$permissions]);
    }


    /**
     * 添加
     * @return string|\yii\web\Response
     */
    public function actionAddPermission(){
        $model = new AddPermission();

        $model->scenario = $model::SCENARIO_ADD;//应用场景
            $request = \Yii::$app->request;//创建请求
            if ($request->isPost) {//判断是否是post请求
                $model->load($request->post());//自动加载post提交的数据
                if ($model->validate()) {//后台验证
                    $authManager = \Yii::$app->authManager;
//添加权限
                    $permission = $authManager->createPermission($model->name);
                    $permission->description = $model->description;//这里会导致提示信息无法弹出
                    if($authManager->add($permission)){
                        \Yii::$app->session->setFlash('success', '添加信息成功!');//设置提示信息
                        return $this->redirect(['rbac/permission-index']);//跳转到主
                    }
                }

            }
        return $this->render('add-permission',['model'=>$model]);
}


    /**
     * 修改权限
     * @param $name
     * @return string|\yii\web\Response
     * @throws HttpException
     */
    public function actionEditPermission($name)
    {
        $authManager = \Yii::$app->authManager;
        $permission = $authManager->getPermission($name);
        if ($permission == null) {
            throw new HttpException(404, '权限不存在');
        }
        $model = new AddPermission();
        $model->scenario = $model::SCENARIO_EDIT;//应用场景
        $model->name = $permission->name;//回显名字
        $model->description = $permission->description;//回显描述

        $request = \Yii::$app->request;//创建请求
        if ($request->isPost) {//判断是否是post请求
            $model->load($request->post());//自动加载post提交的数据
            if ($model->validate()) {//后台验证
//添加权限
                $permission->name = $model->name;//将表单数据赋值给权限对象
                $permission->description = $model->description;

                if ($authManager->update($name, $permission)) {//更新成功
                    \Yii::$app->session->setFlash('success', '添加信息成功!');//设置提示信息
                    return $this->redirect(['rbac/permission-index']);//跳转到主
                }
            }
        }
        return $this->render('add-permission', ['model' => $model]);

    }


    public function actionDeletePermission($name)
    {
        $authManager = \Yii::$app->authManager;
        $permission = $authManager->getPermission($name);

        if ($permission == null) {
            throw new HttpException(404, '权限不存在');
        }
        $authManager->remove($permission);
        \Yii::$app->session->setFlash('success', '删除成功');//设置提示信息
        return $this->redirect(['rbac/permission-index']);//跳转回首页
    }



}

<?php

namespace backend\controllers;

use backend\filters\RbacFilters;
use backend\models\AddPermission;
use backend\models\RoleAdd;
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
     * 添加权限
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
                        //return $this->refresh();
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


    /**
     *删除权限
     * @param $name
     * @return \yii\web\Response
     * @throws HttpException
     */
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


    /**
     *
     * 角色主页
     * @return string
     */
    public function actionRoleIndex()
    {
        $authManager = \Yii::$app->authManager;
        $roles = $authManager->getRoles();
        return $this->render('role-index',['roles'=>$roles]);
    }


    /**
     * 角色添加
     * @return string|\yii\web\Response
     */
    public function actionRoleAdd()
    {
        $authManager = \Yii::$app->authManager;
        $model = new RoleAdd();
        $model->scenario = $model::SCENARIO_ADD;//应用场景
        $items = RoleAdd::getPermissionName();//将权限的数据读取出来
        $request = \Yii::$app->request;//创建请求
        if ($request->isPost) {//判断是否是post请求
            $model->load($request->post());//自动加载post提交的数据
            if ($model->validate()) {//后台验证
//添加权限
                $role = $authManager->createRole($model->name);//创建角色
                $role->description = $model->description;//角色描述
                $authManager->add($role);//添加角色

                if(is_array($model->permission)){//这里判断是为了防止用户没有勾选权限时候报错
                    foreach ($model->permission as $name) {//将用户添加的权限循环输出关联角色
                        $permissionName = $authManager->getPermission($name);//获取角色名
                        $authManager->addChild($role, $permissionName);//权限角色关联
                    }
                }
                \Yii::$app->session->setFlash('success', '添加信息成功!');//设置提示信息
                return $this->redirect(['rbac/role-index']);//跳转到主
            }
        }
        return $this->render('role-add', ['model' => $model, 'items' => $items]);
    }


    /**
     * 角色修改
     * @param $name
     * @return string|\yii\web\Response
     */
    public function actionRoleEdit($name)
    {
        $authManager = \Yii::$app->authManager;
        $role = $authManager->getRole($name);
        $model = new RoleAdd();
        $model->scenario = $model::SCENARIO_EDIT;//应用场景
        $items = RoleAdd::getPermissionName();//将权限的数据读取出来显示到修改页面
        $model->name = $role->name;//回显名字
        $model->description = $role->description;//回显描述
        $permissions = $authManager->getPermissionsByRole($role->name);//根据权限找到所有角色
        $model->permission = [];
        foreach ($permissions as $permission) {
            $model->permission[] = $permission->name;//将循环出来权限赋值给修改表单的权限列表,注意的是必须要用$model->permission[](数组)来存储多个权限
        }
        $request = \Yii::$app->request;//创建请求
        if ($request->isPost) {//判断是否是post请求
            $model->load($request->post());//自动加载post提交的数据
            if ($model->validate()) {//后台验证
//添加权限
                $role->name = $model->name;//将表单数据赋值给权限对象
                $role->description = $model->description;
                $authManager->update($name, $role);
                $authManager->removeChildren($role);//将所有的该角色对应的权限全部删除了,修改保存的时候就不用考虑原来的权限有哪些
                if (is_array($model->permission)) {//这里判断是为了防止用户没有勾选权限时候报错
                    foreach ($model->permission as $name) {//将用户添加的权限循环输出关联角色
                        $permissionName = $authManager->getPermission($name);//获取角色名
                        $authManager->addChild($role, $permissionName);//权限角色关联
                    }
                }
                \Yii::$app->session->setFlash('success', '添加信息成功!');//设置提示信息
                return $this->redirect(['rbac/role-index']);//跳转到主
            }
        }
        return $this->render('role-add', ['model' => $model, 'items' => $items]);
    }


    /**
     * 角色删除
     * @param $name
     * @return \yii\web\Response
     * @throws HttpException
     */
    public function actionRoleDelete($name)
    {
        $authManager = \Yii::$app->authManager;
        $role = $authManager->getRole($name);
        if ($role == null) {
            throw new HttpException(404, '角色不存在');
        }
        $authManager->remove($role);
        \Yii::$app->session->setFlash('success', '删除成功');//设置提示信息
        return $this->redirect(['rbac/role-index']);//跳转回首页
    }





/*    public function behaviors()
    {
        return [
            'rbac' => [
                'class' =>RbacFilters::class
            ],
        ];
    }*/


}

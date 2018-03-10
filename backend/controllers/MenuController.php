<?php

namespace backend\controllers;

use backend\filters\RbacFilters;
use backend\models\Menu;
use backend\models\RoleAdd;
use yii\web\Controller;

class MenuController extends Controller
{
    /**
     * 主页
     * @return string
     */
    public function actionIndex()
    {
        $menuList = Menu::find()->all();

        return $this->render("index", ["menuList" => $menuList]);
    }

    /**
     * 添加
     * @return string|\yii\web\Response
     */
    public function actionAdd()
    {
        $model = new Menu();//创建模型
        $request = \Yii::$app->request;//创建请求
        $items = RoleAdd::getPermissionName();
        $parentIdLists = Menu::getParentIdList();
        if ($request->isPost) {//判断是否是post请求
            $model->load($request->post());//自动加载post提交的数据
            if ($model->validate()) {//后台验证

                $model->save();//保存数据
            } else {
                var_dump($model->getErrors());
                exit;//验证失败,打印出错误信息
            }
            \Yii::$app->session->setFlash('success', '添加信息成功!');//设置提示信息
            //return $this->redirect(['menu/index']);//跳转到主页
            return $this->refresh();//跳转到主页
        }
        return $this->render('add', ['model' => $model, 'items' => $items, 'parentIdLists' => $parentIdLists]);//渲染模型
    }


    /**
     * 修改
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionEdit($id)
    {
        $request = \Yii::$app->request;//创建请求
        $model = Menu::find()->where(['id' => $id])->one();//根据id创建模型
        $parentIdLists = Menu::getParentIdList();
        $items = RoleAdd::getPermissionName();
        if ($request->isPost) {//判断是否post提交
            $model->load($request->post());//自动加载post提交的数据
            if ($model->validate()) {//后台验证
                $model->save();//保存数据
            } else {
                var_dump($model->getErrors());
                exit;//后台验证失败,打印出错误信息
            }
            \Yii::$app->session->setFlash('success', '修改成功!');//设置提示信息
            return $this->redirect(['menu/index']);//跳转
        }
        return $this->render('add', ['model' => $model, 'items' => $items, 'parentIdLists' => $parentIdLists]);//渲染模型
    }

    /**
     * 删除
     * @param $id
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        $model = Menu::find()->where(['id' => $id])->one();//创建模型
        $model->delete();//删除状态改为1
        $model->save();//保存
        \Yii::$app->session->setFlash('success', '删除成功');//设置提示信息
        return $this->redirect(['menu/index']);//跳转回首页
    }






    public function behaviors()
    {
        return [
            'rbac' => [
                'class' =>RbacFilters::class
            ],
        ];
    }

}

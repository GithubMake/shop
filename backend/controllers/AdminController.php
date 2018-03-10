<?php

namespace backend\controllers;

use backend\filters\RbacFilters;
use backend\models\Admin;
use backend\models\ChangePassword;
use backend\models\LoginForm;
use yii\captcha\CaptchaAction;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\User;

class AdminController extends Controller
{
    /**
     * 主页
     * @return string
     */
    public function actionIndex()
    {
        $query = Admin::find();
        $pager = new Pagination();//实例化
        $pager->totalCount = $query->count();//总条数
        $pager->defaultPageSize = 3;//默认每页条数
        $admins = $query->offset($pager->offset)->limit($pager->limit)->all();//所有的员工信息
        return $this->render('index', ['admins' => $admins, 'pager' => $pager]);//回显

    }


    /**
     * 添加
     * @return string|\yii\web\Response
     */
    public function actionAdd()
    {
        $model = new Admin();//创建模型
        $model->scenario = Admin::SCENARIO_ADD;//声明场景
        $authManager = \Yii::$app->authManager;//组件实例化
        $items = Admin::getRoles();//获取所有角色
        $request = \Yii::$app->request;//创建请求
        if ($request->isPost) {//判断是否是post请求
            $model->load($request->post());//自动加载post提交的数据
            //var_dump($_POST);exit;
            if ($model->validate()) {//后台验证
                if ($model->save()) {//先保存用户然后才能获取用户的id值
                    $authManager->revokeAll($model->id);//清除该用户所有角色
                    foreach ($model->roles as $role) {//从角色数组中得到单个角色
                        $role = $authManager->getRole($role);
                        $authManager->assign($role, $model->id);//为该用户添加角色
                    }
                }//保存数据
            } else {
                var_dump($model->getErrors());
                exit;//验证失败,打印出错误信息
            }
            \Yii::$app->session->setFlash('success', '添加信息成功!');//设置提示信息
            return $this->redirect(['admin/index']);//跳转到主页
        }
        return $this->render('add', ['model' => $model, 'items' => $items]);//渲染模型
    }


    /**
     * 修改
     * @param $id
     * @return string|\yii\web\Response
     * @throws HttpException
     */
    public function actionEdit($id)
    {
        $authManager = \Yii::$app->authManager;//组件实例化
        $items = Admin::getRoles();//获取所有角色
        $request = \Yii::$app->request;//获取请求
        $model = Admin::find()->where(['id' => $id])->one();//获取数据
        if (!$model) {
            throw new HttpException(404, '该用户不存在或者已被修改');
        }
        $model->scenario = Admin::SCENARIO_EDIT;//声明场景

        $roles = $authManager->getRolesByUser($id);
        //var_dump($roles);exit;
        $model->roles = [];
        foreach ($roles as $role) {
            $model->roles[] = $role->name;//将循环出来权限赋值给修改表单的权限列表,注意的是必须要用$model->roles[](数组)来存储多个权限
        }
        //判断是否post提价
        if ($request->isPost) {
            //模型自动加载
            $model->load($request->post());
            //后台验证
            if ($model->validate()) {
                //验证通过保存
                $model->save();
                $authManager->revokeAll($id);//清除该用户所有角色
                if ($model->roles) {//判断是否用户勾选了
                    foreach ($model->roles as $role) {//从角色数组中得到单个角色
                        $role = $authManager->getRole($role);
                        $authManager->assign($role, $model->id);//为该用户添加角色
                    }
                }
                //设置提示信息
                \Yii::$app->session->setFlash('success', '修改成功');
                //跳转
                return $this->redirect(['admin/index']);
            } else {
                //验证失败打印出错误
                var_dump($model->getErrors());
                exit;
            }
        }
        //数据回显
        return $this->render('add', ['model' => $model, 'items' => $items]);
    }




    /**
     * 删除
     * @param $id
     * @return \yii\web\Response
     * @throws HttpException
     */
    public function actionDelete($id)
    {
        //找到数据模型
        $model = Admin::find()->where(['id' => $id])->one();
        $authManager = \Yii::$app->authManager;//组件实例化
        //删除
        if(!$model){
            throw new HttpException(404,'该页面已不存在');
        }
        //var_dump($model);exit;
        $model->status = 0;
        $model->delete();
        $authManager->revokeAll($id);//清除该用户所有角色
        $result = $model->save();
        //判断是否删除成功
        if ($result) {
            //删除成功,设置提示信息
            \Yii::$app->session->setFlash('success', '删除数据成功');
            //跳转回首页
            return $this->redirect(['admin/index']);
        }
    }


    /**
     * 登录
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        //登录模型表单
        $model = new LoginForm();
        //数据库模型表单
        $admin = new Admin();
        //请求
        $request = \Yii::$app->request;
        //是否post提交
        if ($request->isPost) {
            //自动加载数据
            $model->load($request->post());
            //后台验证
            if ($model->validate()) {
                //登录验证
                if ($model->login()) {
                    $admin->save();
                    \Yii::$app->session->setFlash('success', '登录成功');//设置提示信息
                    return $this->redirect(['admin/index']);//跳转回登录页
                } else {
                    \Yii::$app->session->setFlash('danger', '登录失败');//设置提示信息
                }
            } else {
                var_dump($model->getErrors());
                exit;
            }
        };
        return $this->render('login', ['model' => $model]);
    }


    /**
     * 退出登录
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        \Yii::$app->session->setFlash('success', '注销成功');
        return $this->redirect(['admin/login']);
    }


    /**
     * 验证码
     * @return array
     */
    public function actions()
    {
        return [
            'captcha' => [
                'class' => CaptchaAction::class,
                'minLength' => 4,
                'maxLength' => 4,
            ]
        ];
    }


    /**
     * 修改密码
     * @return string|\yii\web\Response
     */
    public function actionChangePassword()
    {
        $model = new ChangePassword();
        $request = \Yii::$app->request;
        $user = \Yii::$app->user->identity;
        if (!$user) {
            return $this->redirect(['admin/login']);//没登录,跳转到登录页面
        }
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                $user->password_hash = \Yii::$app->security->generatePasswordHash($model->newPassword);
                $user->save();
                //注销
                \Yii::$app->user->logout();
                \Yii::$app->session->setFlash('success', '密码修改成功,请使用新密码登录');
                return $this->redirect(['admin/login']);
            }
        }
        $model->username = $user->username;
        return $this->render('changePassword', ['model' => $model]);
    }





    /**
     * 过滤器
     * @return array
     */
    public function behaviors()
    {
        return [
            'rbac' => [
                'class' => RbacFilters::class,
                'except'=>['login','logout','change-password','captcha','']
            ],
        ];
    }

}

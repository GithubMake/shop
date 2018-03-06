<?php

namespace backend\controllers;

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
        $request = \Yii::$app->request;//创建请求
        if ($request->isPost) {//判断是否是post请求
            $model->load($request->post());//自动加载post提交的数据
            if ($model->validate()) {//后台验证
                $model->save();//保存数据
            } else {
                var_dump($model->getErrors());
                exit;//验证失败,打印出错误信息
            }
            \Yii::$app->session->setFlash('success', '添加信息成功!');//设置提示信息
            return $this->redirect(['admin/index']);//跳转到主页
        }
        return $this->render('add', ['model' => $model]);//渲染模型
    }


    /**
     * 修改
     * @param $id
     * @return string|\yii\web\Response
     * @throws HttpException
     */
    public function actionEdit($id)
    {
        //获取请求
        $request = \Yii::$app->request;
        //获取数据
        $model = Admin::find()->where(['id' => $id])->one();
        if (!$model) {
            throw new HttpException(404, '该用户不存在或者已被修改');
        }
        $model->scenario = Admin::SCENARIO_EDIT;//声明场景
        //判断是否post提价
        if ($request->isPost) {
            //模型自动加载
            $model->load($request->post());
            //后台验证
            if ($model->validate()) {
                //验证通过保存
                $model->save();
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
        return $this->render('add', ['model' => $model]);
    }


    /**
     * 删除
     * @param $id
     */
    public function actionDelete($id)
    {
        //找到数据模型
        $model = Admin::find()->where(['id' => $id])->one();
        //删除
        $model->status = 0;
        $result = $model->save();
        //判断是否删除成功
        if ($result) {
            //删除成功,设置提示信息
            \Yii::$app->session->setFlash('success', '删除数据成功');
            //跳转回首页
            $this->redirect('index');
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
                    /* $id = \Yii::$app->user->id;
                     $time = Admin::find()->select(['last_login_time','last_login_ip'])->where(['id'=>$id])->one();
                     $time['last_login_time'] = time();
                     $time['last_login_ip'] = \Yii::$app->request->getRemoteIP();
                     $admin->last_login_time= $time['last_login_time'];
                     $admin->last_login_ip =$time['last_login_ip'];*/
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
                'class' => CaptchaAction::className(),
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
}

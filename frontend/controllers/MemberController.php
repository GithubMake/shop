<?php

namespace frontend\controllers;

use frontend\models\Login;
use frontend\models\Member;

class MemberController extends \yii\web\Controller
{


    public function actionRegister(){
        $model = new Member();//创建模型
        $request = \Yii::$app->request;//创建请求
        if ($request->isPost) {//判断是否是post请求
            $model->load($request->post(),'');//自动加载post提交的数据
            //var_dump($_POST['password']);exit;
            if ($model->validate()) {//后台验证
                $model->status = 1;
                $model->created_at = time();
                $model->last_login_time = time();
                $model->last_login_ip = "127.0.0.1";
                $model->auth_key = \Yii::$app->security->generateRandomString();//生成随机字符串
                $model->password_hash = \Yii::$app->security->generatePasswordHash($_POST['password']);//对密码进行加密
               // var_dump($model);exit;
                $model->save();//保存数据
            } else {
                var_dump($model->getErrors());
                exit;//验证失败,打印出错误信息
            }
            \Yii::$app->session->setFlash('success', '添加信息成功!');//设置提示信息
            return $this->redirect(['member/login']);//跳转到主页
        }
        return $this->render('register');
    }



    public function  actionLogin(){
        //登录模型表单
        $model = new Login();
        //数据库模型表单
        $member =new Member();
        //请求
        $request = \Yii::$app->request;
        //是否post提交
        if($request->isPost){
            //自动加载数据
            $model->load($request->post(),'');
            //后台验证
            if($model->validate()){
                //登录验证
                    if($model->login()){
                        $member->save();
                    }
                    \Yii::$app->session->setFlash('success', '登录成功');//设置提示信息
                    return $this->redirect(['member/address']);//跳转回登录页
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('login');
    }






    public function actionAddress()
    {
        return $this->render('address');
    }

}

<?php

namespace backend\models;

use yii\base\Model;

class LoginForm extends Model{

    public $username;
    public $password;//登录表单的明文密码
    public $code;//验证码
    public $rememberMe;//自动登录的记住我

    public  function  attributeLabels(){
       return[
           'username'=>'用户名',
           'password'=>'密码',
           'code'=>'验证码',
           'rememberMe'=>'记住我'
           ];
    }

    public function rules(){
        return[
            [['username','password'],'required'],
            ['code','captcha','captchaAction'=>'admin/captcha'],//使用的是student/captcha的验证码
            ['rememberMe','safe'],//记住我可不验证
        ];
    }


    public function login()
    {
        $username = Admin::findOne(['username' => $this->username]);
        if ($username) {//用户名存在
            if (\Yii::$app->security->validatePassword($this->password, $username->password_hash)) {//密码正确
                $username->last_login_time = time();
                $username->last_login_ip=
                $duration =$this->rememberMe?15*24*3600:0;//用户信息保存的时间
                return \Yii::$app->user->login($username,$duration);
            } else {//密码不正确
                $this->addError('password','账号或者密码不正确');
            };
        } else {//用户名不存在
                $this->addError('username','账号或者密码不正确');
        }
        return false;
    }






}
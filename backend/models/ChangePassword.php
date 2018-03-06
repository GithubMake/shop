<?php
namespace  backend\models;

use yii\base\Model;

class ChangePassword extends Model{
    public $username;
    public $oldPassword;
    public $newPassword;
    public $rePassword;

    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'oldPassword'=>'旧密码',
            'newPassword'=>'新密码',
            'rePassword'=>'确认密码',
        ];
    }



    public function rules()
    {
        return [
            [['oldPassword','newPassword','rePassword'],'required'],
            ['rePassword','compare','compareAttribute'=>'newPassword','message'=>'两次密码不一致'],//验证新密码和确认密码一样
            ['oldPassword','validateOldPassword'],//自定义验证旧密码正确性
            ['newPassword','validateNewPassword'],//自定义新旧密码不能一样
        ];
    }

//自定义的验证规则
    public function validateOldPassword(){
        $result = \Yii::$app->security->validatePassword($this->oldPassword,\Yii::$app->user->identity->password_hash);
        if($result===false){
            $this->addError('oldPassword','旧密码不正确');
        }
    }

//自定义的验证规则
    public function validateNewPassword(){
        $result = \Yii::$app->security->validatePassword($this->newPassword,\Yii::$app->user->identity->password_hash);
        if($result){
            $this->addError('newPassword','新旧密码一致');
        }
    }
};
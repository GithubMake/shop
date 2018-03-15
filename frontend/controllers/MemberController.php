<?php

namespace frontend\controllers;

use frontend\aliyun\SignatureHelper;
use frontend\models\Address;
use frontend\models\Login;
use frontend\models\Member;
use yii\widgets\Menu;

class MemberController extends \yii\web\Controller
{

    public  $enableCsrfValidation =false;

    public function actionRegister(){
        $model = new Member();//创建模型
        $request = \Yii::$app->request;//创建请求
        if ($request->isPost) {//判断是否是post请求
            $model->load($request->post(),'');//自动加载post提交的数据
            //var_dump($_POST['password']);exit;
            if ($model->validate()) {//后台验证
                $model->status = 1;
                $model->created_at = time();
                $model->updated_at = time();
                $model->last_login_time = time();
                $model->last_login_ip = "127.0.0.1";
                $model->auth_key = \Yii::$app->security->generateRandomString();//生成随机字符串
                $model->password_hash = \Yii::$app->security->generatePasswordHash($_POST['password']);//对密码进行加密
                //var_dump($model);exit;
                if($model->save()){

                }else{
                    var_dump($model->getErrors());exit;
                }
                //保存数据
            } else {
                var_dump($model->getErrors());
                exit;//验证失败,打印出错误信息
            }
            \Yii::$app->session->setFlash('success', '添加信息成功!');//设置提示信息
            return $this->redirect(['member/login']);//跳转到主页
        }
        return $this->render('register');
    }





    /**
     * 验证用户名
     * @param $username
     * @return string
     */
    public function  actionValidateUsername($username){
        $usernameDb = Member::find()->where(['username'=>$username])->one();
        if($username != $usernameDb){
            return "true";
        }else{
            return "false";
        }
    }






    public function actionValidateEmail($email){
        $result = Member::find()->where(['email'=>$email])->one();
        if($result){
            return "true";
        }else{
            return "false";
        }
    }




    public function actionValidateTel($tel){
        $result = Member::find()->where(['tel'=>$tel])->one();
        if($result){
            return "true";
        }else{
            return "false";
        }
    }
    /**
     * 发送短信验证码
     * @param $tel
     * @return string
     */
    public function actionSendMessage($tel){
        $code = mt_rand(100000,999999);//生成随机六位字符串保存起来
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        //var_dump($r);exit;
        $redis->set('code_'.$tel,$code,10*60);//六位验证码保存在redis中
        $result =\Yii::$app->sms->getTel($tel)->getTemplateParam(['code'=>$code])->sendSms();//发送短信
        if($result){//判断发送
            return "true";
        }else{
            return "false";
        }
    }



    public function actionValidateMessage($tel,$code){
        $redis = new \Redis();
        $redis->connect('127.0.0.1',6379);
        $codeCloud = $redis->get('code_'.$tel);
        if($codeCloud == $code){
            return "true";
        }else{
            return  "false";
        }
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
            //var_dump($model);exit;
            //后台验证
            if($model->validate()){
                //登录验证
                    if($model->login()){
                        $member->save();
                        \Yii::$app->session->setFlash('success', '登录成功');//设置提示信息
                        //判断是否cookie中是否存在url地址,存在就跳转到购物车的订单页面,没有地址就跳转到商品列表页
                        if($url = \Yii::$app->request->cookies->getValue('url')){
                            return $this->redirect(['cart/cart-order']);
                        } else{
                            return $this->redirect(['goods/goods-category']);
                        }
                    }
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('login');
    }



    public function actionLogout()
    {
        \Yii::$app->user->logout();
        \Yii::$app->session->setFlash('success', '注销成功');
        return $this->redirect(['member/login']);
    }

    /**
     * 地址列表
     * @return string
     */
    public function actionAddress()
    {
        $member_id = \Yii::$app->user->id;
        $address = Address::find()->where(['member_id'=>$member_id])->all();
        return $this->render('address',['address'=>$address]);
    }

    /**
     * 添加收货地址
     * @return string|\yii\web\Response
     */
    public function actionAddressAdd()
    {
        $model = new Address();//创建模型
        $request = \Yii::$app->request;//创建请求
        if ($request->isPost) {//判断是否是post请求
            $model->load($request->post(), '');//自动加载post提交的数据
            $model->province = $request->post('cmbProvince');
            $model->city = $request->post('cmbCity');
            $model->area = $request->post('cmbArea');
            $member_id = \Yii::$app->user->id;
            $model->member_id = $member_id;
            if ($model->validate()) {//后台验证
                //判断用户是否登录,未登录就跳转到登录页面,登录以后判断是否将该地址设置为默认地址
                if ($member_id) {
                    if ($request->post('status')) {
                        $model->status = 1;
                    } else {
                        $model->status = 0;
                    }
                } else {
                    return $this->redirect(['member/login']);
                }
                $model->save();//保存数据
                \Yii::$app->session->setFlash('success', '添加信息成功!');//设置提示信息
                return $this->redirect(['member/address']);//跳转到主页
            } else {
                var_dump($model->getErrors());
                exit;//验证失败,打印出错误信息
            }
        }
        return $this->render('addressAdd');
    }

//修改没写...............
    public function actionAddressEdit($id){
        $request = \Yii::$app->request;
        $model = Address::findOne(["id" =>$id]);
        if ($request->isPost) {
            $model->load($request->post(), "");
            $model->province = $request->post('cmbProvince');
            $model->city = $request->post('cmbCity');
            $model->area = $request->post('cmbArea');
            $member_id = \Yii::$app->user->id;
            $model->member_id = $member_id;
            if ($model->validate()) {
                if($request->post("status")) {
                    $model->status = 1;
                } else {
                    $model->status = 0;
                }
                $model->save();
            }
        }
        return $this->render('addressEdit',['model'=>$model]);
    }

    /**
     *删除地址
     * @param $id
     * @return \yii\web\Response
     */
    public function actionAddressDelete($id)
    {
        $model = Address::find()->where(['id' => $id])->one();//根据id创建模型
        $model->delete();//删除
        //var_dump( $model->delete());exit;
        //$model->save();//保存
        \Yii::$app->session->setFlash('success', '删除成功');//设置提示信息
        return $this->redirect(['member/address']);//跳转

    }
}



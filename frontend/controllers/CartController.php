<?php

namespace frontend\controllers;

use backend\models\Goods;
use frontend\models\Cart;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Cookie;
use yii\web\HttpException;

class CartController extends \yii\web\Controller
{

  //  public  $enableCsrfValidation =false;

    public function actionCartInfo($goods_id,$amount)
    {
        //判断用户是否登录,没有登录将购物车信息保存在cookie中,登录了将购物车信息保存在数据库
/*        if(\Yii::$app->user->isGuest){
            //键保存商品值保存商品数量
            $cart = [];
            $cart[$goods_id] = $amount;

            //将商品信息保存在cookie中,cookiede值只能保存字符串,必须要将值序列化
            $cookie = new Cookie();
            $cookie->name = 'cart';
            $cookie->value = serialize($cart);
            $cookie->expire = 365 * 24 * 60 * 60;
            \Yii::$app->response->cookies->add($cookie);
            //获取购物车中商品
            $cart = \Yii::$app->request->cookies->getValue('cart');
            //判断cookie中是否保存了购物车信息,有的话就反序列化取出来
            if ($cart) {
                $cart = unserialize($cart);
            } else {
                $cart = [];
            }
            //如果用户反复添加数据的话就要更新购物车的信息,对于相同的商品采用累加,不同的商品就直接保存商品id和商品数量
            if (array_key_exists($goods_id,$cart)) {
                $cart[$goods_id] += $amount;
            } else {
                $cart[$goods_id] = $amount;
            }
        }else{*/
            $model  = new Cart();
              if ($model->validate()) {//后台验证
                  $model->amount = $amount;
                  $model->goods_id = $goods_id;
                  $model->member_id = 1;
                  $model->save();//保存数据
              }else{
                  var_dump($model->getErrors());
                  exit;//验证失败,打印出错误信息
              }
        //}
        return $this->render('cart-info');
    }



    public function actionCart()
    {
        $carts = Cart::find()->all();
        return $this->render('cart',['carts'=>$carts]);
    }


    public function actionCartAjax($goods_id, $amount)
    {
        //var_dump($goods_id,$amount);
        /*if(\Yii::$app->user->isGuest){
            //键保存商品值保存商品数量
            $cart = [];
            $cart[$goods_id] = $amount;

            //将商品信息保存在cookie中,cookiede值只能保存字符串,必须要将值序列化
            $cookie = new Cookie();
            $cookie->name = 'cart';
            $cookie->value = serialize($cart);
            $cookie->expire = 365 * 24 * 60 * 60;
            \Yii::$app->response->cookies->add($cookie);
            //获取购物车中商品
            $cart = \Yii::$app->request->cookies->getValue('cart');
            //判断cookie中是否保存了购物车信息,有的话就反序列化取出来
            if ($cart) {
                $cart = unserialize($cart);
            } else {
                $cart = [];
            }
            //直接更新用户的商品数量,,包含加,减,手动输入,如果是删除就直接将购物车中的商品id删除
            if ($amount) {
                $cart[$goods_id] = $amount;
            } else {
                unset($cart[$goods_id]);
            }
        }else{*/
        //通过判断数商品的数量确定是删除还是其他的操作数量,删除时候数量为0,其他操作都不为0

                $model  = Cart::find()->where(['goods_id'=>$goods_id])->one();
                if ($model->validate()) {//后台验证
                    if($amount){
                        $model->amount = $amount;
                        $model->goods_id = $goods_id;
                        $model->member_id = 1;
                        $model->save();
                    }else{
                        $model->delete();
                    }
                }else{
                    var_dump($model->getErrors());
                    exit;//验证失败,打印出错误信息
                }

        }

    //}



    public function actionCartOrder(){
        //判断是否登录,未登录,将当前地址保存在cookie中,跳转到登录页面,登录跳转到订单页面
        if(\Yii::$app->user->isGuest){
            $url = \Yii::$app->request->getHostInfo().\Yii::$app->request->url;
            $cookie = new Cookie();
            $cookie->name = 'url';
            $cookie->value = serialize($url);
            $cookie->expire =  24 * 60 * 60;
            \Yii::$app->response->cookies->add($cookie);
            return $this->redirect(['member/login']);
        }else{
            //会员...
            $carts = Cart::find()->all();
            return $this->render('cart-order',['carts'=>$carts]);
        }




    }


    public function actionCartAdd(){
        $cart = new Cart();
        $request = \Yii::$app->request;
        var_dump($request->isPost);exit;
        if($request->isPost){
            $re = $cart->load($request->post() ,'');
            var_dump($re);exit;
            try{

            }catch (Exception $e){

            }
        }

    }
}

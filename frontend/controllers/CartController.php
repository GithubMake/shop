<?php

namespace frontend\controllers;

use backend\models\Goods;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Delivery;
use frontend\models\Order;
use frontend\models\OrderGoods;
use frontend\models\Payment;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Cookie;
use yii\web\HttpException;

class CartController extends \yii\web\Controller
{

    public  $enableCsrfValidation =false;

    /**
     * 购物车信息提示页
     * @param $goods_id
     * @param $amount
     * @return string
     */
    public function actionCartInfo($goods_id,$amount)
    {
        //判断用户是否登录,没有登录将购物车信息保存在cookie中,登录了将购物车信息保存在数据库
        if(\Yii::$app->user->isGuest){
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
        }else {
            ////用户是直接登录添加商品,cookie中没有值,直接将数据保存在数据库就行,未登录添加商品,则在用户登陆的时候将cookie中的值同步到数据库中,并且清除cookie中的内容,如果有这个商品就合并cookie中的数量,如果没有就创建这个商品信息
            $cart = \Yii::$app->request->cookies->getValue('cart');
            //判断cookie中是否保存了购物车信息,有的话就反序列化取出来
            if ($cart) {
                $cart = unserialize($cart);
                $model = Cart::find()->andWhere(['member_id' => \Yii::$app->user->id])->andwhere(['goods_id' => $cart->goods_id])->one();
                if ($model) {
                    $model->amount += $cart->amount;
                    $model->save();//保存数据
                    unset($cart[$goods_id]);
                }
            } else {
                $model = new Cart();
                $request = \Yii::$app->request;
                $model->load($request->get(), '');
                if ($model->validate()) {//后台验证
                    $model->member_id = \Yii::$app->user->id;
                    $model->save();//保存数据
                } else {
                    var_dump($model->getErrors());
                    exit;//验证失败,打印出错误信息
                }
            }
        }
        return $this->render('cart-info');
    }


    /**
     * 购物车页
     * @return string
     */
    public function actionCart()
    {
        $carts = Cart::find()->where(['member_id'=>\Yii::$app->user->id])->all();
        return $this->render('cart',['carts'=>$carts]);
    }


    /**
     *
     * @param $goods_id
     * @param $amount
     */
    public function actionCartAjax($goods_id, $amount)
    {
        if (\Yii::$app->user->isGuest) {
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
        } else {
            //通过判断数商品的数量确定是删除还是其他的操作数量,删除时候数量为0,其他操作都不为0

            $model = Cart::find()->where(['goods_id' => $goods_id])->one();
            if ($model->validate()) {//后台验证
                if ($amount) {
                    $model->amount = $amount;
                    $model->goods_id = $goods_id;
                    $model->member_id = 1;
                    $model->save();
                } else {
                    $model->delete();
                }
            } else {
                var_dump($model->getErrors());
                exit;//验证失败,打印出错误信息
            }

        }

    }


    /**
     * 购物车订单
     * @return string|\yii\web\Response
     */
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

            $id = \Yii::$app->user->id;
            $address = Address::find()->where(['member_id'=>$id])->all();
            $carts = Cart::find()->all();
            $delivery = Delivery::find()->all();
            $payment = Payment::find()->all();
            return $this->render('cart-order',['carts'=>$carts,'address'=>$address,'delivery'=>$delivery,'payment'=>$payment]);
        }




    }


    public function actionCartAdd()
    {
        $request = \Yii::$app->request;
        //判断是否订单页面是否将地址和快递方式和支付方式都提交过来,没有提交过来就跳转回订单页面
        if (!$request->post('address_id') && $request->post('delivery_id') && $request->post('payment_id')) {
            return $this->redirect(['cart/cart-order']);
        }
        if ($request->isPost) {
            $order = new Order();
            //加载地址和快递方式和支付方式的数据
            $address = Address::find()->where(['id' => $request->post('address_id')])->one();
            $delivery = Delivery::find()->where(['delivery_id' => $request->post('delivery_id')])->one();
            $payment = Payment::find()->where(['payment_id' => $request->post('payment_id')])->one();
            $order->member_id = $address->member_id;
            $order->name = $address->username;
            $order->province = $address->province;
            $order->city = $address->city;
            $order->area = $address->area;
            $order->address = $address->detail_address;
            $order->tel = $address->tel;
            $order->delivery_id = $request->post('delivery_id');
            $order->delivery_name = $delivery->delivery_name;
            $order->delivery_price = $delivery->delivery_price;
            $order->payment_id = $payment->payment_id;
            $order->payment_name = $payment->payment_name;
            $order->total = $delivery->delivery_price;//先保存运费,后面将商品的价格加上去.
            $order->status = 1;//设置一个默认支付状态
            $order->trade_no = \Yii::$app->security->generateRandomString();
            $order->create_time = time();
            //开启事务
            $transaction = \Yii::$app->db->beginTransaction();
                try {
                    var_dump($order);exit;
                   // $order->save();
                    var_dump($order->save());exit;
                $carts = Cart::find()->where(['member_id' => \Yii::$app->user->id])->all();
                //var_dump($carts);exit;
                foreach ($carts as $cart) {
                    //检查库存是否足够,足够就保存商品信息,库存不够抛出异常
                    //echo 111;exit;
                    $goods = Goods::find()->where(['id' => $cart->goods_id])->one();
                    if ($goods->stock < $order->amount) {

                        //throw new Exception('商品[' . $goods->name . ']库存不足');
                    }
                    var_dump($goods);exit;
                    $goods->stock -= $cart->amount;
                    $goods->save();
                    $orderGoods = new OrderGoods();
                    $orderGoods->order_id = $order->id;
                    $orderGoods->goods_id = $goods->id;
                    $orderGoods->goods_name = $goods->name;
                    $orderGoods->logo = $goods->logo;
                    $orderGoods->price = $goods->shop_price;
                    $orderGoods->amount = $cart->amount;
                    $orderGoods->total = $orderGoods->price * $orderGoods->amount;
                    $order->total += $orderGoods->total;
                    $orderGoods->save();
                    $order->save();
                }
                //添加完所有商品清除订单
                Cart::deleteAll(['member_id' => $order->member_id]);
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        }

    }






}

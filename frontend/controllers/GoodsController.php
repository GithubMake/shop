<?php

namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class GoodsController extends Controller
{

    public function actionGoodsCategory()
    {
        $goodsFirsts = GoodsCategory::find()->where(['parent_id'=>0])->all();

        //var_dump($goodsFirsts);exit;
        return $this->render('goods-category',['goodsFirsts'=>$goodsFirsts]);
    }





    public function actionGoods(){


        return $this->render('goods');
    }


    public function actionGoodsList()
    {

        return $this->render('goods-list');
    }

}

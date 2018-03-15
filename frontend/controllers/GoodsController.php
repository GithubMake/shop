<?php

namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class GoodsController extends Controller
{

    /**
     * 展示商品的无限极分类树
     * @return string
     */
    public function actionGoodsCategory()
    {
        $goodsFirsts = GoodsCategory::find()->where(['parent_id'=>0])->all();

        return $this->render('goods-category',['goodsFirsts'=>$goodsFirsts]);
    }


    /**
     *,商品列表,不管点击哪一级都可以查看商品
     * @param $id
     * @return string
     */
    public function actionGoodsList($id)
    {
        $goodsCategories = GoodsCategory::findOne(['id' => $id]);
        //处理分类不存在的情况
        switch ($goodsCategories->depth) {
            case 0://1级分类
            case 1://2级分类
                $ids = $goodsCategories->children()->select(['id'])->andWhere(['depth' => 2])->asArray()->column();
                break;
            case 2://3级分类
                $ids = [$id];
                break;
        }
        $goods = Goods::find()->where(['in', 'goods_category_id', $ids])->all();
        return $this->render('goods-list', ['goods' => $goods]);
    }


    /**
     * 商品详情页
     * @param $id
     * @return string
     */
    public function actionGoods($id){
        $goods = Goods::find()->where(['id'=>$id])->one();
        return $this->render('goods',['goods'=>$goods]);
    }




}

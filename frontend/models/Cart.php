<?php
namespace frontend\models;

use backend\models\Goods;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Cart extends ActiveRecord{
    public function rules()
    {
        return [
            ['goods_id','safe'],
            ['amount','safe'],
            ['member_id','safe'],
        ];
    }


    /**
     * 获取商品名称展示在购物车页面
     * @return array
     */
    public static function getGoodsName(){
        $goods = Goods::find()->asArray()->all();
        return  ArrayHelper::map($goods,'id','name');

    }

    /**
     * 获取商品市场价格展示在购物车页面
     * @return array
     */
    public static function getGoodsMarketPrice(){
        $goods = Goods::find()->asArray()->all();
        return  ArrayHelper::map($goods,'id','market_price');

    }

    /**
     * 获取商品图片展示在购物车页面
     * @return array
     */
    public static function getGoodsLogo(){
        $goods = Goods::find()->asArray()->all();
        return  ArrayHelper::map($goods,'id','logo');

    }


}
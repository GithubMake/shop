<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "goods".
 *
 * @property int $id
 * @property string $name 名称
 * @property string $sn 货号
 * @property string $logo logo
 * @property int $goods_category_id 商品分类
 * @property int $brand_id 品牌分类
 * @property string $mark_price 市场价格
 * @property string $shop_price 商品价格
 * @property int $stock 库存
 * @property int $is_on_sale 是否在售
 * @property int $status 状态
 * @property int $sort 排序
 * @property string $create_time 添加时间
 * @property int $view_times 浏览次数
 */
class Goods extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'goods_category_id', 'brand_id', 'market_price', 'shop_price', 'stock', 'is_on_sale','sort','logo'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [

            'name' => '名称',
            'sn' => '货号',
            'logo' => '图片',
            'goods_category_id' => '商品分类',
            'brand_id' => '品牌分类',
            'market_price' => '市场价格',
            'shop_price' => '商品价格',
            'stock' => '库存',
            'is_on_sale' => '是否在售',
            'sort' => '排序',
        ];
    }

    /**
     * 自动生成货号
     * @param $count
     * @return string
     */
    public static  function  getSn($number){
        $ymd = date('Ymd',time()).sprintf("%05d", $number);
        return $ymd;
    }

    /**
     * 添加商品分类下拉框
     * @return array
     */
    public static function getGoodsCategory(){
        $goodsCategories = GoodsCategory::find()->asArray()->all();//查询出文章
        return  ArrayHelper::map($goodsCategories,'id','name');

    }

    /**
     * 添加皮牌分类下拉框
     * @return array
     */
    public static function getBrand(){
        $brands = Brand::find()->asArray()->all();//查询出文章
        return  ArrayHelper::map($brands,'id','name');
    }

}

<?php

namespace backend\controllers;

use backend\filters\RbacFilters;
use backend\models\Goods;
use backend\models\GoodsIntro;
use yii\web\Controller;

class GoodsIntroController extends Controller
{
    public function actionContent($id)
    {
        $goods = Goods::find()
            ->where(['id' => $id])
            ->one();
        $goodsIntro = GoodsIntro::find()
            ->where(['goods_id' => $id])
            ->one();//创建模型
        return $this->render(
            'index',
            ['goodsIntro' => $goodsIntro,
                'goods' => $goods]);
    }





/*    public function behaviors()
    {
        return [
            'rbac' => [
                'class' =>RbacFilters::class
            ],
        ];
    }*/

}

<?php
/**
 * Created by PhpStorm.
 * User: asus-pc
 * Date: 2018/3/1
 * Time: 11:38
 * 以下代码为GoodsCategory中find方法服务
 *
 */

namespace backend\models;

use yii\db\ActiveRecord;
use creocoder\nestedsets\NestedSetsQueryBehavior;

/*class GoodsCategoryQuery extends ActiveRecord{

    public function behaviors() {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}*/

//这里使用的是ActiveQuery,而不是ActiveRecord
class GoodsCategoryQuery extends \yii\db\ActiveQuery
{
    public function behaviors() {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}


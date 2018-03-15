<?php
namespace frontend\models;

use yii\db\ActiveRecord;

class Delivery extends ActiveRecord{
    public function rules()
    {
        return [
            ['delivery_name','delivery_price','required'],
        ];
    }
}
<?php
namespace frontend\models;

use yii\db\ActiveRecord;

class Payment extends ActiveRecord{
    public function rules()
    {
        return [
            ['payment_name'],
        ];
    }
}
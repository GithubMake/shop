<?php

namespace frontend\models;


use yii\base\Model;
use yii\db\ActiveRecord;

class Address extends ActiveRecord {

    public function rules()
    {
        return [

            [['username','city','area','detail_address','tel','province',],'required'],
            [['status'],'safe'],
        ];
    }

}
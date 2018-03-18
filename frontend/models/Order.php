<?php
namespace frontend\models;



use yii\db\ActiveRecord;

class Order extends ActiveRecord{


  public function rules()
  {
      return [
          ['member_id','name','province','city','area','address','tel','tel','delivery_id','delivery_name','delivery_price','payment_id','payment_name','total','status','trade_no','required'],
      ];
  }


}
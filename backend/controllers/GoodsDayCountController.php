<?php

namespace backend\controllers;

use backend\filters\RbacFilters;
use yii\web\Controller;

class GoodsDayCountController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }


    public function behaviors()
    {
        return [
            'rbac' => [
                'class' =>RbacFilters::class
            ],
        ];
    }



}

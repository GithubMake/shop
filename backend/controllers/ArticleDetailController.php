<?php
namespace backend\controllers;

use backend\filters\RbacFilters;
use backend\models\Article;
use backend\models\ArticleDetail;
use yii\web\Controller;

class ArticleDetailController extends Controller
{
    public function actionContent($id)
    {
        $article = Article::find()
            ->where(['id' => $id])
            ->one();
        $articleDetail = ArticleDetail::find()
            ->where(['article_id' => $id])
            ->one();//创建模型
        return $this->render(
            'index',
            ['articleDetail' => $articleDetail,
                'article' => $article]);
    }



/*
    public function behaviors()
    {
        return [
            'rbac' => [
                'class' =>RbacFilters::class
            ],
        ];
    }*/

}
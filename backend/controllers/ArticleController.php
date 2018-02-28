<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleDetail;
use yii\data\Pagination;
use yii\web\Controller;

class ArticleController extends Controller
{
    /**
     * 主页
     * @return string
     */
    public function actionIndex()
    {
        $pager = new Pagination();//实例化分页组件
        $pager->totalCount = Article::find()->count();//总条数
        $pager->defaultPageSize = 3;//默认每页条数3
        $articles = Article::find()->offset($pager->offset)->limit($pager->limit)->all();//查询全部数据
        return $this->render('index', ['articles' => $articles, 'pager' => $pager]);
    }

    /**
     * 添加
     * @return string|\yii\web\Response
     */
    public function actionAdd()
    {
        $article = new Article();//创建文章数据库表单模型
        $articleDetail = new ArticleDetail();//创建文章详情数据表模型
        $request = \Yii::$app->request;//创建请求
        if ($request->isPost) {//判断是否post请求
            $article->load($request->post());//自动加载
            $articleDetail->load($request->post());//自动加载
            if ($article->validate() && $articleDetail->validate()) {//后台验证
                $article->create_time = time();
                $article->save();
                $articleDetail->save();
            } else {
                var_dump($article->getErrors());
                exit;//验证失败打印错误信息
            }
            \Yii::$app->session->setFlash('success', '添加成功');//设置提示信息
            return $this->redirect(['article/index']);//跳转
        }
        return $this->render('add', ['article' => $article, 'articleDetail' => $articleDetail]);//渲染模型
    }

    /**
     * 修改
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionEdit($id)
    {
        $article = Article::find()->where(['id' => $id])->one();//根据id创建文章模型
        $articleDetail = ArticleDetail::find()->where(['article_id' => $id])->one();//根据id创建文章内容
        $request = \Yii::$app->request;//创建请求
        if ($request->isPost) {//判断是否post提交
            $article->load($request->post());//自动加载数据
            $articleDetail->load($request->post());
            if ($article->validate() && $articleDetail->validate()) {//后台验证
                $article->create_time = time();
                $article->is_deleted = 0;
                $article->save();//验证成功,保存数据
                //$content->save();
            } else {
                var_dump($article->getErrors());
                exit;//后台验证失败打印错误信息
            }
            \Yii::$app->session->setFlash('success', '修改成功');//设置提示信息
            return $this->redirect(['article/index']);//跳转回主页
        }
        return $this->render('add', ['article' => $article, 'articleDetail' => $articleDetail]);//渲染页面
    }

    /**
     *逻辑删除
     * @param $id
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        $model = Article::find()->where(['id' => $id])->one();//根据id创建模型
        $model->is_deleted = 1;//逻辑删除
        $model->save();//保存
        \Yii::$app->session->setFlash('success', '删除成功');//设置提示信息
        return $this->redirect(['article/index']);//跳转
    }

}

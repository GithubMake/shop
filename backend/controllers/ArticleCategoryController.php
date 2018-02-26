<?php
namespace backend\controllers;

use yii\data\Pagination;
use yii\web\Controller;

class ArticleCategoryController extends Controller{
    /**
     * 主页
     * @return string
     */
    public function actionIndex(){
        $pager = new Pagination();//实例化分页组件
        $pager->totalCount=\ArticleCategory::find()->count();//count统计总条数
        $pager->defaultPageSize=3;//自己设定的每页条数
        $articleCategories = \ArticleCategory::find()->offset($pager->offset)->limit($pager->limit)->all();//查询出文章分类总条数
        return $this->render('index',['articleCategories'=>$articleCategories,'pager'=>$pager]);//渲染页面
    }

    /**
     * 添加
     * @return string|\yii\web\Response
     */
    public function actionAdd(){
        $model = new \ArticleCategory();//创建模型
        $request = \Yii::$app->request;//创建请求模型
        if($request->isPost){//判断是否post提交
            $model->load($request->post());//模型自动加载提交的数据
            if($model->validate()){//后台验证
                $model->save();//验证通过保存数据
            }else{
                var_dump($model->getErrors());exit;//验证不通过,打印错误信息
            }
            \Yii::$app->session->setFlash('success','添加成功');//设置跳转信息
            return $this->redirect(['articleCategory/index']);//跳转到文章分类首页
        }
        return $this->render('add',['model'=>$model]);//渲染模型
    }

    /**
     * 修改
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionEdit($id){
        $model = \ArticleCategory::findOne(['id'=>$id]);//创建模型
        $request = \Yii::$app->request;//创建请求
        if($request->isPost){//判断是否post提交
            $model->load($request->post());//模型自动加载数据
            if($model->validate()){//后台验证
                $model->save();//验证成功,保存数据
            }else{
                var_dump($model->getErrors());exit;//后台验证失败打印错误信息
            }
            \Yii::$app->session->setFlash('success','修改成功');//设置提示信息
            return $this->redirect(['articleCategory/index']);//跳转回主页
        }
        return $this->render('edit',['model'=>$model]);//渲染页面
    }
    public function actionDelete($id){
        $model = \ArticleCategory::find()->where(['id'=>$id])->one();//根据id创建模型
        $result = $model->delete();//删除该数据
        if($result){//删除数据成功
            \Yii::$app->session->setFlash('success','删除成功');//设置提示信息
            return $this->redirect(['articleCategory/index']);//跳转
        }
    }
}
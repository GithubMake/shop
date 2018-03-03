<?php

namespace backend\controllers;

use backend\models\GoodsCategory;
use yii\data\Pagination;
use yii\web\Controller;

class GoodsCategoryController extends Controller
{
    /**
     * 主页
     * @return string
     */
    public function actionIndex()
    {
        $pager = new Pagination();//实例化分页组件
        $pager->totalCount = GoodsCategory::find()->count();//总条数
        $pager->defaultPageSize = 3;//默认每页条数3
        $goodsCategories = GoodsCategory::find()->offset($pager->offset)->limit($pager->limit)->all();//查询全部数据
                return $this->render('index', ['goodsCategories' => $goodsCategories, 'pager' => $pager]);
    }

    /**
     * 添加
     * @return string|\yii\web\Response
     */
    public function actionAdd()
    {
        $model = new  GoodsCategory();//创建模型
        $request = \Yii::$app->request;//创建请求
        if ($request->isPost) {//判断是否post提交数据
            $model->load($request->post());//自动加载数据
            if ($model->validate()) {//后台验证
                if ($model->parent_id) {//是否父id
                    $parent = GoodsCategory::findOne(['id' => $model->parent_id]);
                    $model->appendTo($parent);//添加子类
                } else {
                    if ($model->getOldAttribute("parent_d") === 0) {
                        $model->save();
                    } else {
                        $model->makeRoot();
                    }
                }
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['goods-category/index']);
            } else {
                var_dump($model->getErrors());
                exit;//验证失败打印出错误信息
            }
        }
        $nodes = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        $nodes[]=['id'=>0,'parent_id'=>0,'name'=>'一级分类'];
        $nodes = json_encode($nodes);
        return $this->render('add', ['model' => $model,'nodes'=>$nodes]);//渲染模型
    }

    /**
     *修改
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionEdit($id)
    {
        $request = \Yii::$app->request;//创建请求
        $model = GoodsCategory::find()->where(['id' => $id])->one();//根据id创建模型
        if ($request->isPost) {//判断是否post提交
            $model->load($request->post());//自动加载post提交的数据
            if ($model->validate()) {//后台验证
                $model->save();
                \Yii::$app->session->setFlash('success', '修改成功!');//设置提示信息
                return $this->redirect(['goods-category/index']);//跳转
            } else {
                var_dump($model->getErrors());
                exit;//后台验证失败,打印出错误信息
            }
        }
        $nodes = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        $nodes[]=['id'=>0,'parent_id'=>0,'name'=>'一级分类'];
        $nodes = json_encode($nodes);
        return $this->render('add', ['model' => $model,'nodes'=>$nodes]);//渲染模型
    }

    public function actionDelete($id)
    {
        $model = GoodsCategory::find()->where(['id' => $id])->one();//根据id创建模型
        if($model->parent_id){
            $model->delete();
        }else{
            $model->deleteWithChildren();
        }
        $model->save();//保存
        \Yii::$app->session->setFlash('success', '删除成功');//设置提示信息
        return $this->redirect(['article/index']);//跳转
    }
}

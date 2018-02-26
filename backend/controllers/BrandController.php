<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{
    /**
     * 主页
     * @return string
     */
    public function actionIndex()
    {
        $pager = new Pagination();//实例化分页组件
        $pager->totalCount = Brand::find()->count();//总条数,要使用count统计条数,不能使用all来查询数据
        $pager->defaultPageSize = 3;//每页默认条数
        $brands = Brand::find()->offset($pager->offset)->limit($pager->limit)->all();//查询出brand的全部数据
        return $this->render('index',['brands'=>$brands,'pager'=>$pager]);//渲染主页
    }

    /**
     * add
     * @return string|\yii\web\Response
     */
    public function actionAdd(){
        $model = new Brand();//创建模型
        $request = \Yii::$app->request;//创建请求
        if($request->isPost){//判断是否是post请求
            $model->load($request->post());//自动加载post提交的数据
            $model->imgFile = UploadedFile::getInstance($model,'imgFile');//实例化自动上传文件属性
            if($model->validate()){//后台验证
                if($model->imgFile){//判断是否上传文件
                    $file = '/upload/'.uniqid().'.'.$model->imgFile->extension;//相对路径用于存数据库中
                    if($model->imgFile->saveAs(\Yii::getAlias('@webroot').$file,0)){//如果上传成功
                        $model->logo = $file;//绝对路径保存到logo属性中
                    }
                }
                $model->save();//保存数据
            }else{
                var_dump($model->getErrors());exit;//验证失败,打印出错误信息
            }
            \Yii::$app->session->setFlash('success','添加信息成功!');//设置提示信息
            return $this->redirect(['brand/index']);//跳转到主页
        }

        return $this->render('add',['model'=>$model]);//渲染模型
    }

    /**
     * 修改
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionEdit($id){
        $request = \Yii::$app->request;//创建请求
        $model = Brand::find()->where(['id'=>$id])->one();//根据id创建模型
        if($request->isPost){//判断是否post提交
            $model->load($request->post());//自动加载post提交的数据
            if($model->validate()){//后台验证
                $model->save();//保存数据
            }else{
                var_dump($model->getErrors());exit;//后台验证失败,打印出错误信息
            }
            \Yii::$app->session->setFlash('success','修改成功!');//设置提示信息
            return $this->redirect(['brand/index']);//跳转
        }
        return  $this->render('add',['model'=>$model]);//渲染页面
    }
    public function actionDelete($id){
        $model = Brand::find()->where(['id'=>$id])->one();//创建模型
        $result = $model->delete();//删除
        if($result){
            \Yii::$app->session->setFlash('success','删除成功');//设置提示信息
             $this->redirect(['brand/index']);//跳转回首页
        }
    }
}

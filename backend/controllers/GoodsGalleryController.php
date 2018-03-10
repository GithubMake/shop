<?php

namespace backend\controllers;

use backend\filters\RbacFilters;
use backend\models\GoodsGallery;
use yii\web\Controller;
use yii\web\UploadedFile;

class GoodsGalleryController extends Controller
{


    public  $enableCsrfValidation =false;//防止post跨域攻击关闭

    /**
     * 主页
     * @return string
     */
    public function actionIndex()
    {
        $goodsGalleries = GoodsGallery::find()->all();

        return $this->render('index', ['goodsGalleries' => $goodsGalleries]);
    }


    /**
     * 添加
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionAdd($id)
    {
        $model = new GoodsGallery();//创建商品模型
        $request = \Yii::$app->request;//创建请求
        if ($request->isPost) {//判断是否是post请求
            $model->load($request->post());//自动加载post提交的数据
            if ($model->validate()) {//后台验证
                $model->goods_id = $id;
                $model->save();//保存数据
            } else {
                var_dump($model->getErrors());
                exit;//验证失败,打印出错误信息
            }
            \Yii::$app->session->setFlash('success', '添加信息成功!');//设置提示信息
            return $this->redirect(['goods-gallery/index']);//跳转到主页
        }

        return $this->render('add', ['model' => $model]);//渲染模型
    }






    /**
     * 文件上传
     * @return string
     */
    public function actionLogoUpload()
    {
        $upLoadedFile = UploadedFile::getInstanceByName('file');//实例化上传类
        $fileName = '/upload/' . uniqid() . '.' . $upLoadedFile->extension;//文件路径
        $result = $upLoadedFile->saveAs(\Yii::getAlias('@webroot') . $fileName);//保存图片
        if ($result) {
            return json_encode([
                'url' => $fileName,
            ]);
        }

    }





    /**
     * 删除
     * @param $id
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        $model = GoodsGallery::find()->where(['id' => $id])->one();//创建模型
        $model->delete();//删除状态改为1
        $model->save();//保存
        \Yii::$app->session->setFlash('success', '删除成功');//设置提示信息
        return $this->redirect(['goods-gallery/index']);//跳转回首页
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

<?php

namespace backend\controllers;

use backend\filters\RbacFilters;
use backend\models\GoodsGallery;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\UploadedFile;

class GoodsGalleryController extends Controller
{


    public  $enableCsrfValidation =false;//防止post跨域攻击关闭

    /**
     * 主页
     * @return string
     */
    public function actionIndex($id)
    {
var_dump($id);
/*        $goodsGallery = new GoodsGallery();
        $goodsGallery->goods_id = $id;//将商品的id保存进goods_id字段*/
        $cookie = new Cookie();

        $cookie->name = 'goods_id';

        $cookie->value = serialize($id);
        $cookie->expire = 7 * 24 * 60 * 60;

        \Yii::$app->response->cookies->add($cookie);

        //$goods_id = \Yii::$app->request->cookies->getValue('goods_id');
        //$goods_id = unserialize($goods_id);
        // var_dump($cookie);exit;
        //  var_dump($goods_id);exit;
        $goodsGalleries = GoodsGallery::find()->where(['goods_id'=>$id])->all();

        return $this->render('index', ['goodsGalleries' => $goodsGalleries]);
    }


    public function actionAdd()
    {
        $model = new GoodsGallery();//创建模型
        $request = \Yii::$app->request;//创建请求
        if ($request->isPost) {//判断是否是post请求
            $model->load($request->post());//自动加载post提交的数据
            if ($model->validate()) {//后台验证
                $goods_id = \Yii::$app->request->cookies->getValue('goods_id');
                $model->goods_id = unserialize($goods_id);
                $model->save();//保存数据
            } else {
                var_dump($model->getErrors());
                exit;//验证失败,打印出错误信息
            }
            \Yii::$app->session->setFlash('success', '添加信息成功!');//设置提示信息
            return $this->redirect(['goods_galley/index','id'=>$model->goods_id]);//跳转到主页
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
        //var_dump($upLoadedFile);exit;
        $fileName = '/upload/' . uniqid() . '.' . $upLoadedFile->extension;//文件路径
        //var_dump($fileName);exit;
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
        return $this->redirect(['goods-gallery/index','goods_id'=>$model->id]);//跳转回首页
    }





/*    public function behaviors()
    {
        return [
            'rbac' => [
                'class' =>RbacFilters::class,
                'except'=>['logo-Upload'],
            ],
        ];
    }*/


}

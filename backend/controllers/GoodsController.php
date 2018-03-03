<?php

namespace backend\controllers;

use backend\models\Goods;
use backend\models\GoodsIntro;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\UploadedFile;

class GoodsController extends Controller
{
    public function actionIndex()
    {
        $query =  Goods::find()->where(['status'=>1]);//用于逻辑删除
        $pager = new Pagination();//实例化分页组件
        $pager->totalCount = $query->count();//总条数,要使用count统计条数,不能使用all来查询数据
        $pager->defaultPageSize = 3;//每页默认条数
        $goods = $query->offset($pager->offset)->limit($pager->limit)->all();//查询出brand的全部数据
        return $this->render('index',['goods'=>$goods,'pager'=>$pager]);//渲染主页
    }


    public function actionAdd(){
        $model = new Goods();//创建商品模型
        $goodsIntro = new GoodsIntro();//创建商品描述模型
        $request = \Yii::$app->request;//创建请求
        if($request->isPost){//判断是否是post请求
            $model->load($request->post());//自动加载post提交的数据
            $goodsIntro->load($request->post());
            if($model->validate() && $goodsIntro->validate()){//后台验证
                if($model->sn){//判断是否有货号
                    $sn = $model->sn;
                    $sn++;
                    var_dump($sn);exit();
                }else{
                    $count = 1;//第1个货号
                    $model->sn = Goods::getSn($count);
                }
                $model->status =1;//默认状态值是正常
                $model->create_time =time();//创建时间
                $goodsIntro->goods_id =$model->id;
                $goodsIntro->save();
                $model->save();//保存数据
            }else{
                var_dump($model->getErrors());exit;//验证失败,打印出错误信息
            }
            \Yii::$app->session->setFlash('success','添加信息成功!');//设置提示信息
            return $this->redirect(['goods/index']);//跳转到主页
        }

        return $this->render('add',['model'=>$model,'goodsIntro'=>$goodsIntro]);//渲染模型
    }



    public function actionEdit($id){
        $request = \Yii::$app->request;//创建请求
        $model = Goods::find()->where(['id'=>$id])->one();//根据id创建模型
        $goodsIntro = GoodsIntro::find()->where(['goods_id'=>$id])->one();//根据id创建模型
        if($request->isPost){//判断是否post提交
            $model->load($request->post());//自动加载post提交的数据
            $goodsIntro->load($request->post());
            if($model->validate() && $goodsIntro->validate()){//后台验证
                $goodsIntro->goods_id =$model->id;
                $goodsIntro->save();
                $model->save();//保存数据
                \Yii::$app->session->setFlash('success','修改成功!');//设置提示信息
                return $this->redirect(['goods/index']);//跳转
            }else{
                var_dump($model->getErrors());exit;//后台验证失败,打印出错误信息
            }
        }
        return  $this->render('add',['model'=>$model,'goodsIntro'=>$goodsIntro]);//渲染页面
    }

    public function actionLogoUpload()
    {
        $upLoadedFile = UploadedFile::getInstanceByName('file');//实例化上传类
        $fileName = '/upload/' . uniqid() . '.' . $upLoadedFile->extension;//文件路径
        $result = $upLoadedFile->saveAs(\Yii::getAlias('@webroot') . $fileName);//保存图片
        if($result){
            return json_encode([
                'url' => $fileName,
            ]);
        }
    }


    public function actionDelete($id){
        $model = Goods::find()->where(['id'=>$id])->one();//创建模型
        $model->status = 0;//删除状态改为0
        $model->save();//保存
        \Yii::$app->session->setFlash('success','删除成功');//设置提示信息
        return $this->redirect(['goods/index']);//跳转回首页
    }

}

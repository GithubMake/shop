<?php

namespace backend\controllers;

use backend\filters\RbacFilters;
use backend\models\Brand;
use yii\data\Pagination;
use yii\web\UploadedFile;

// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;

class BrandController extends \yii\web\Controller
{



    public  $enableCsrfValidation =false;//关闭防止post跨域攻击




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
        return $this->render('index', ['brands' => $brands, 'pager' => $pager]);//渲染主页
    }






    /**
     * 添加
     * add
     * @return string|\yii\web\Response
     */
    public function actionAdd()
    {
        $model = new Brand();//创建模型
        $request = \Yii::$app->request;//创建请求
        if ($request->isPost) {//判断是否是post请求
            $model->load($request->post());//自动加载post提交的数据
            //$model->imgFile = UploadedFile::getInstance($model,'imgFile');//实例化自动上传文件属性
            if ($model->validate()) {//后台验证
//                if($model->imgFile){//判断是否上传文件
//                    $file = '/upload/'.uniqid().'.'.$model->imgFile->extension;//相对路径用于存数据库中
//                    if($model->imgFile->saveAs(\Yii::getAlias('@webroot').$file,0)){//如果上传成功
//                        $model->logo = $file;//绝对路径保存到logo属性中
//                    }
//                }
                $model->save();//保存数据
            } else {
                var_dump($model->getErrors());
                exit;//验证失败,打印出错误信息
            }
            \Yii::$app->session->setFlash('success', '添加信息成功!');//设置提示信息
            return $this->redirect(['brand/index']);//跳转到主页
        }

        return $this->render('add', ['model' => $model]);//渲染模型
    }





    /**
     * 修改
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionEdit($id)
    {
        $request = \Yii::$app->request;//创建请求
        $model = Brand::find()->where(['id' => $id])->one();//根据id创建模型
        if ($request->isPost) {//判断是否post提交
            $model->load($request->post());//自动加载post提交的数据
            if ($model->validate()) {//后台验证
                $model->save();//保存数据
            } else {
                var_dump($model->getErrors());
                exit;//后台验证失败,打印出错误信息
            }
            \Yii::$app->session->setFlash('success', '修改成功!');//设置提示信息
            return $this->redirect(['brand/index']);//跳转
        }
        return $this->render('add', ['model' => $model]);//渲染页面
    }






    /**
     * 逻辑删除
     * @param $id
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        $model = Brand::find()->where(['id' => $id])->one();//创建模型
        $model->is_deleted = 1;//删除状态改为1
        $model->save();//保存
        \Yii::$app->session->setFlash('success', '删除成功');//设置提示信息
        return $this->redirect(['brand/index']);//跳转回首页
    }






    /**
     * 图片上传控制器
     * @return array
     */
    public function actionLogoUpload()
    {
        $upLoadedFile = UploadedFile::getInstanceByName('file');//实例化上传类
        $fileName = '/upload/' . uniqid() . '.' . $upLoadedFile->extension;//文件路径
        $result = $upLoadedFile->saveAs(\Yii::getAlias('@webroot') . $fileName);//保存图片
        if ($result) {
            //文件保存成功
            //将图片上传到七牛云
            // 需要填写你的 Access Key 和 Secret Key
            $accessKey = "UxBdl5LVS1gE8e8xVyc-zBnNzqXxxbU9-dcy0gSW";
            $secretKey = "xg2-LTp3fHuYyOJXu_L7uMUnS47SzFyfO5IznenQ";
            //自己七牛云存储空间的名称
            $bucket = "shop";
            // 构建鉴权对象
            $auth = new Auth($accessKey, $secretKey);
            // 生成上传 Token
            $token = $auth->uploadToken($bucket);
            // 要上传文件的本地路径
            $filePath = \Yii::getAlias('@webroot') . $fileName;
            // 上传到七牛后保存的文件名
            $key = $fileName;
            // 初始化 UploadManager 对象并进行文件的上传。
            $uploadMgr = new UploadManager();
            // 调用 UploadManager 的 putFile 方法进行文件的上传。
            list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
            if ($err == null) {
                //上传七牛云成功
                //访问七牛云图片的地址http://七牛云的测试域名地址/上传骑牛云保存的文件名
                return json_encode([
                    'url' => "http://p4uxftgjl.bkt.clouddn.com/{$key}"
                ]);
            } else {//上传失败返回错误信息
                return json_encode([
                    'url' => $err
                ]);
            }
        } else {//文件保存失败,返回错误标志字段fail
            return json_encode([
                'url' => "fail"
            ]);
        }
    }


    /**
     * 过滤器
     * @return array
     */
    public function behaviors()
    {
        return [
            'rbac' => [
                'class' => RbacFilters::class,
                'except'=>['logo-upload'],
            ],
        ];
    }
}

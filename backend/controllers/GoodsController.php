<?php

namespace backend\controllers;

use backend\filters\RbacFilters;
use backend\models\Goods;
use backend\models\GoodsDayCount;
use backend\models\GoodsIntro;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\UploadedFile;

class GoodsController extends Controller
{



    public  $enableCsrfValidation =false;//防止post跨域攻击关闭





    /**
     * 主页
     * @return string
     */
    public function actionIndex()
    {
        $request = \Yii::$app->request;//请求
        $query = Goods::find()->andWhere(['status' => 1]);//用于逻辑删除
        $name = $request->get('name');//姓名字段
        if ($name) {
            $query->andWhere(['like', 'name', $name]);
        }
        $sn = $request->get('sn');//货号字段
        if ($sn) {
            $query->andWhere(['like', 'sn', $sn]);
        }
        $minPrice = $request->get('minPrice');//最低价格
        if ($minPrice) {
            $query->andWhere(['>', 'market_price', $minPrice]);
        }
        $maxPrice = $request->get('maxPrice');//最低价格
        if ($maxPrice) {
            $query->andWhere(['<', 'market_price', $maxPrice]);
        }

        $pager = new Pagination();//实例化分页组件
        $pager->totalCount = $query->count();//总条数,要使用count统计条数,不能使用all来查询数据
        $pager->defaultPageSize = 6;//每页默认条数
        $goods = $query->offset($pager->offset)->limit($pager->limit)->all();//查询出brand的全部数据
        return $this->render('index', ['goods' => $goods, 'pager' => $pager]);//渲染主页
    }



    /**
     * 添加
     * @return string|\yii\web\Response
     */
    public function actionAdd()
    {
        $model = new Goods();//创建商品模型
        $goodsIntro = new GoodsIntro();//创建商品描述模型
        $request = \Yii::$app->request;//创建请求
        if ($request->isPost) {//判断是否是post请求
            $model->load($request->post());//自动加载post提交的数据
            $goodsIntro->load($request->post());
            if ($model->validate() && $goodsIntro->validate()) {//后台验证
                $model->sn = Goods::getSn(self::getGoodsDayCount());
                //var_dump($model->sn);exit;
                $model->status = 1;//默认状态值是正常
                $model->create_time = time();//创建时间
                $model->view_times = 0;
                $goodsIntro->goods_id = $model->id;
                $goodsIntro->save();
                $model->save();//保存数据
            } else {
                var_dump($model->getErrors());
                exit;//验证失败,打印出错误信息
            }
            \Yii::$app->session->setFlash('success', '添加信息成功!');//设置提示信息
            return $this->redirect(['goods/index']);//跳转到主页
        }
        /* $nodes = Goods::find()->select(['id','goods_category_id','name'])->asArray()->all();
         $nodes[]=['id'=>0,'goods_category_id'=>0,'name'=>'顶级分类'];
         $nodes = json_encode($nodes);
         return $this->render('add',['model'=>$model,'goodsIntro'=>$goodsIntro,'nodes'=>$nodes]);//渲染模型*/
        return $this->render('add', ['model' => $model, 'goodsIntro' => $goodsIntro,]);//渲染模型
    }


    /**
     * 用于保存商品时间数量,同时返回数量用于创建货号
     * @return int|mixed
     */
    public static function getGoodsDayCount()
    {
        $unixTime = strtotime(date('Y-m-d'));//获取每天的凌晨时间
        $goodsDayCounts = GoodsDayCount::find()->andwhere(['>', 'day', $unixTime])->all();//将所有大于该时间的数据取出来
        //var_dump($goodsDayCounts);exit;
        if($goodsDayCounts==null){
            $goodsDayCount  =  new GoodsDayCount();
            $goodsDayCount->day = time();
            $goodsDayCount->count = 1;//直接统计为第一个
            //犯了一个错误没有将count返回,导致goods表的sn一直是默认的货号
            if( $goodsDayCount->save()){
                return $goodsDayCount->count;
            }else{
                var_dump($goodsDayCount->getErrors());exit;
            }



        }else{
            foreach ($goodsDayCounts as $goodsDayCount) {//循环出每一组数据
                $result = $goodsDayCount->day > $unixTime;//比较每一组的时间是否大于凌晨时间
                if ($result) {//大于,证明数据库中已经有数据
                    $goodsDayCount->count = $goodsDayCount->count + 1;//统计字段加1
                    $goodsDayCount->save();
                    return $goodsDayCount->count;//返回统计数量字段用于,构造货号sn
                }
            }
        }
    }




    /**
     * 修改
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionEdit($id)
    {
        $request = \Yii::$app->request;//创建请求
        $model = Goods::find()->where(['id' => $id])->one();//根据id创建模型
        $goodsIntro = GoodsIntro::find()->where(['goods_id' => $id])->one();//根据id创建模型
        if ($request->isPost) {//判断是否post提交
            $model->load($request->post());//自动加载post提交的数据
            $goodsIntro->load($request->post());
            if ($model->validate() && $goodsIntro->validate()) {//后台验证
                $goodsIntro->goods_id = $model->id;
                $goodsIntro->save();
                $model->save();//保存数据
                \Yii::$app->session->setFlash('success', '修改成功!');//设置提示信息
                return $this->redirect(['goods/index']);//跳转
            } else {
                var_dump($model->getErrors());
                exit;//后台验证失败,打印出错误信息
            }
        }
        return $this->render('add', ['model' => $model, 'goodsIntro' => $goodsIntro]);//渲染页面
    }






    /**
     * 添加页面的图片上传处理
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
        $model = Goods::find()->where(['id' => $id])->one();//创建模型
        $model->status = 0;//删除状态改为0
        $model->save();//保存
        \Yii::$app->session->setFlash('success', '删除成功');//设置提示信息
        return $this->redirect(['goods/index']);//跳转回首页
    }







    /**
     * ueditor
     * @return array
     */
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix" => "http://www.baidu.com",//图片访问路径前缀
                    "imagePathFormat" => "/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}",//上传保存路径
                    "imageRoot" => \Yii::getAlias("@webroot"),
                ],
            ]
        ];
    }



/*    public function behaviors()
    {
        return [
            'rbac' => [
                'class' =>RbacFilters::class,
                'except'=>['logo-upload'],
            ],
        ];
    }*/

}

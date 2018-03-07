<?php
/**
 * Created by PhpStorm.
 * User: asus-pc
 * Date: 2018/3/2
 * Time: 10:53
 */

$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();//name
echo $form->field($model,'logo')->hiddenInput();//logo

//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

$this->registerCssFile('@web/webuploader-0.1.5/webuploader.css');//加载css文件
$this->registerJsFile(
    '@web/webuploader-0.1.5/webuploader.js',
    ['depends'=>\yii\web\JqueryAsset::class]//className与class是一样的效果,由于composer重新安装使yii2框架的版本也升级了,在高版本的框架中,className被纳入弃用的行列中,所以改用class
);//加载js文件,并且解决依赖关系,此处js需要jQuery文件,depends配置使jQuery在js的前面加载,从而保证程序的合理运行
/**
 * 加载HTML代码,用于写上传图片按钮
 */
echo <<<html
<!--dom结构部分-->
<div id="uploader-demo">
    <!--用来存放item-->
    <div id="fileList" class="uploader-list"></div>
    <!--这里有一个坑,就是webuploader的官网上没有给class这个样式,然而他的样式里面又存在,所以需要自己添加一个class="webuploader-pick"才能显示出来样式-->
    <div id="filePicker" >选择图片</div>
</div>
html;
/**
 *
 * 加载js
 */
$goods_upload_url = \yii\helpers\Url::to(['goods/logo-upload']);
$this->registerJs(
    <<<JS
// 初始化Web Uploader
var uploader = WebUploader.create({

    // 选完文件后，是否自动上传。
    auto: true,

    // swf文件路径
    swf:'/webuploader-0.1.5/Uploader.swf',

    // 文件接收服务端。
    server: '{$goods_upload_url}',

    // 选择文件的按钮。可选。
    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
    pick: '#filePicker',

    // 只允许选择图片文件。
    accept: {
        title: 'Images',
        extensions: 'gif,jpg,jpeg,bmp,png',
        mimeTypes: 'image/gif,image/jpg,image/jpeg,image/bmp,image/png'
    }
});
/**
* 上传文件成功触发的监听事件
*/
uploader.on( 'uploadSuccess', function( file,response ) {
  var imgUrl = response.url;//js中获取响应中的内容
   $('#goods-logo').val(imgUrl);//将图片地址复制给logo字段,goods-logo是通过F12打开控制台的查看输入框中的id查看的
   $('#logo-view').attr('src',imgUrl);//图片回显
});
JS
);
echo '<img id="logo-view" width="300px"/>';//用于显示图片
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>




//##############################商品分类开始#####################################
echo $form->field($model,'goods_category_id')->dropDownList([
    \backend\models\Goods::getGoodsCategory()
]);
//#############################商品分类结束######################################
echo $form->field($model,'brand_id')->dropDownList([
    \backend\models\Goods::getBrand(),
]);//brand_id
echo $form->field($model,'market_price')->textInput();//market_price
echo $form->field($model,'shop_price')->textInput();//shop_price
echo $form->field($model,'stock')->textInput();//stock
echo $form->field($model,'is_on_sale',['inline'=>1])->radioList([
    1=>'上线',
    0=>'下线',
]);//is_on_sale
echo $form->field($model,'sort')->textInput();//sort
echo $form->field($goodsIntro,'content')->widget('kucha\ueditor\UEditor',[
    'clientOptions' => [
        //编辑区域大小
        'initialFrameHeight' => '200',
        //设置语言
        'lang' =>'zh-cn', //中文为 zh-cn
        //定制菜单
        'toolbars' => [
            [
                'fullscreen', 'source', 'undo', 'redo', '|',
                'fontsize',
                'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'removeformat',
                'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|',
                'forecolor', 'backcolor', '|',
                'lineheight', '|',
                'indent', '|'
            ],
        ]
    ]
]);//content
echo '<button type="submit" class="btn btn-success">提交</button>';
\yii\bootstrap\ActiveForm::end();
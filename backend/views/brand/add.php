<?php
/**
 * 此注释用于告诉编辑器这是视图,然后敲代码的时候有提示
 * @var $this \yii\web\View
 */
$form =  yii\bootstrap\ActiveForm::begin();//form开始
echo $form->field($model,'name')->textInput();//name
echo $form->field($model,'intro')->textarea();//intro
echo $form->field($model,'logo')->hiddenInput();//imgFile
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
/**
 * 加载css和js代码文件
 */
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
 * 加载js
 */
$brand_upload_url = \yii\helpers\Url::to(['brand/logo-upload']);
$this->registerJs(
<<<JS
// 初始化Web Uploader
var uploader = WebUploader.create({

    // 选完文件后，是否自动上传。
    auto: true,

    // swf文件路径
    swf:'/webuploader-0.1.5/Uploader.swf',

    // 文件接收服务端。
    server: '{$brand_upload_url}',

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
   $('#brand-logo').val(imgUrl);//将图片地址复制给imgFile字段,brand-imgFile是通过F12打开控制台的查看输入框中的id查看的
   $('#logo-view').attr('src',imgUrl);//图片回显
});
JS
);
echo '<img id="logo-view" width="300px"/>';//用于显示图片
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
echo $form->field($model,'sort')->textInput();//sort
echo '<button type="submit" class="btn btn-primary">提交</button>';
\yii\bootstrap\ActiveForm::end();//form结束
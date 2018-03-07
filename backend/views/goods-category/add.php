<?php

$form =  \yii\bootstrap\ActiveForm::begin();//form开始
echo $form->field($model,'name')->textInput();//name
echo $form->field($model,'parent_id')->hiddenInput();//parent_id,该输入框通过用户选择分类集合来自动填写,要隐藏

/**
 * 引入css js文件
 */
$this->registerCssFile('@web/ztree/css/zTreeStyle/zTreeStyle.css');//注册css文件
$this->registerJsFile('@web/ztree/js/jquery.ztree.core.js',$option=[
    'depends'=>\yii\web\JqueryAsset::class
]);//jquery在js之前加载

/**
 * 加载html
 */
echo '<div>
    <ul id="treeDemo" class="ztree"></ul>
</div>';

/**
 * 加载js
 */
$this->registerJs(
   <<<JS
   var zTreeObj;
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
        var setting = {
            data: {
                simpleData: {
                    enable: true,
                    idKey: "id",
                    pIdKey: "parent_id",
                    rootPId: 0
                }
            },
            callback:{
//treeNode代表你点击分类事件,
// treeNode.id代表你的点击事件的id值
//#goodscategory-parent_id代表隐藏的parent_id字段输入框的的id值,是通过打开控制台看到的
                onClick: function(event, treeId, treeNode) {
                    $('#goodscategory-parent_id').val(treeNode.id);
                }  
            }   
        };
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes = {$nodes};  
            zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
            zTreeObj.expandAll(true);//展开全部节点
            zTreeObj.selectNode(zTreeObj.getNodeByParam("id", "{$model->parent_id}",null));
JS
);

echo $form->field($model,'intro')->textarea();//intro
echo '<button type="submit" class="btn btn-success">提交</button>';
\yii\bootstrap\ActiveForm::end();//form结束
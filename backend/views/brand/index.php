<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <style>
         img{
             width: 50px;
         }
    </style>
</head>
<body>
<table class="table table-borderd table-hover">
    <tr>
        <td>logo</td>
        <td>主键</td>
        <td>名称</td>
        <td>简介</td>
        <td>排序</td>
        <td>状态</td>
        <td>操作</td>
    </tr>
    <?php foreach ($brands as $brand):?>
    <tr>
        <td><?php echo \yii\bootstrap\Html::img($brand->logo)?></td>
        <td><?php echo $brand->id?></td>
        <td><?php echo $brand->name?></td>
        <td><?php echo $brand->intro?></td>
        <td><?php echo $brand->sort?></td>
        <td><?php echo (($brand->is_deleted)==0)?'正常':'删除'?></td>
        <td>
            <?PHP echo \yii\bootstrap\Html::a('修改',['brand/edit','id'=>$brand->id],$option = ['class'=>'btn btn-danger'])?>
            <?php echo \yii\bootstrap\Html::a('删除',['brand/delete','id'=>$brand->id],$option = ['class'=>'btn btn-warning'])?>
        </td>
        </tr>
    <?php endforeach;?>
    <tr>
        <td colspan="7" style="text-align: center">
            <?php echo \yii\bootstrap\Html::a('添加',['brand/add'],['class'=>'btn btn-info'])?>
        </td>
    </tr>
</table>
</body>

</html>
<?php
echo yii\widgets\LinkPager::widget([
        'pagination'=>$pager,
        'prevPageLabel'=>'上一页',
        'nextPageLabel'=>'下一页',
]);
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
        <td>主键</td>
        <td>名称</td>
        <td>简介</td>
        <td>排序</td>
        <td>状态</td>
        <td>操作</td>
    </tr>
    <?php foreach ($articleCategories as $articleCategory):?>
        <tr>
            <td><?php echo $articleCategory->id?></td>
            <td><?php echo $articleCategory->name?></td>
            <td><?php echo $articleCategory->intro?></td>
            <td><?php echo $articleCategory->sort?></td>
            <td><?php echo (($articleCategory->is_deleted)==0)?'正常':'删除'?></td>
            <td>
                <?PHP echo \yii\bootstrap\Html::a('修改',['article-category/edit','id'=>$articleCategory->id],$option = ['class'=>'btn btn-danger'])?>
                <?php echo \yii\bootstrap\Html::a('删除',['article-category/delete','id'=>$articleCategory->id],$option = ['class'=>'btn btn-warning'])?>
            </td>
        </tr>
    <?php endforeach;?>
    <tr>
        <td colspan="6" style="text-align: center">
            <?php echo \yii\bootstrap\Html::a('添加',['article-category/add'],['class'=>'btn btn-info'])?>
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
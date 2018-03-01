<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<table class="table table-borderd table-hover">
    <tr>
        <td>主键</td>
        <td>名称</td>
        <td>简介</td>
        <td>操作</td>
    </tr>
    <?php foreach ($goodsCategories as $goodsCategory):?>
        <tr>
            <td><?php echo $goodsCategory->id?></td>
            <td><?php echo $goodsCategory->name?></td>
            <td><?php echo $goodsCategory->intro?></td>
            <td>
                <?PHP echo \yii\bootstrap\Html::a('修改',['goods-category/edit','id'=>$goodsCategory->id],$option = ['class'=>'btn btn-danger'])?>
                <?php echo \yii\bootstrap\Html::a('删除',['goods-category/delete','id'=>$goodsCategory->id],$option = ['class'=>'btn btn-warning'])?>
            </td>
        </tr>
    <?php endforeach;?>
    <tr>
        <td colspan="6" style="text-align: center">
            <?php echo \yii\bootstrap\Html::a('添加',['goods-category/add'],['class'=>'btn btn-info'])?>
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
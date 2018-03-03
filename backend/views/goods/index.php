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
        <td>名称</td>
        <td>货号</td>
        <td>商品分类</td>
        <td>品牌分类</td>
        <td>市场价格</td>
        <td>商品价格</td>
        <td>库存</td>
        <td>是否在售</td>
        <td>状态</td>
        <td>排序</td>
        <td>添加时间</td>
        <td>浏览次数</td>
        <td>操作</td>
    </tr>
    <?php foreach ($goods as $good):?>
    <tr>
        <td><?php echo \yii\bootstrap\Html::img($good->logo)?></td>
        <td><?php echo $good->name?></td>
        <td><?php echo $good->sn?></td>
        <td><?php echo \backend\models\Goods::getGoodsCategory()[$good->goods_category_id]?></td>
        <td><?php echo \backend\models\Goods::getBrand()[$good->brand_id]?></td>
        <td><?php echo $good->market_price?></td>
        <td><?php echo $good->shop_price?></td>
        <td><?php echo $good->stock?></td>
        <td><?php echo (($good->is_on_sale)==0)?'下架':'在售'?></td>
        <td><?php echo (($good->status)==0)?'回收站':'正常'?></td>
        <td><?php echo $good->sort?></td>
        <td><?php echo $good->create_time?></td>
        <td><?php echo $good->view_times?></td>
        <td>
            <?PHP echo \yii\bootstrap\Html::a('相册',['goods-gallery/index','id'=>$good->id],$option = ['class'=>'btn btn-success'])?>
            <?PHP echo \yii\bootstrap\Html::a('详情',['goods-intro/content','id'=>$good->id],$option = ['class'=>'btn btn-primary'])?>
            <?PHP echo \yii\bootstrap\Html::a('修改',['goods/edit','id'=>$good->id],$option = ['class'=>'btn btn-danger'])?>
            <?php echo \yii\bootstrap\Html::a('删除',['goods/delete','id'=>$good->id],$option = ['class'=>'btn btn-warning'])?>
        </td>
        </tr>
    <?php endforeach;?>
    <tr>
        <td colspan="15" style="text-align: center">
            <?php echo \yii\bootstrap\Html::a('添加',['goods/add'],['class'=>'btn btn-info'])?>
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
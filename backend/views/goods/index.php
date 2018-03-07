<!--搜索表单开始-->
    <form action="/goods/index" method="get">
        <input type="text" name="name" placeholder="商品名称" style="width: 20%">
        <input type="text" name="sn" placeholder="货号" style="width: 20%">
        <input type="text" name="minPrice" placeholder="最低价格" style="width: 20%" >
        <input type="text" name="maxPrice" placeholder="最高价格" style="width: 20%">
        <button type="submit" class="btn btn-primary">搜索</button>
        <button type="reset" class="btn btn-primary">重置</button>
    </form>

<!--搜索表单结束-->

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
        <td><?php echo \yii\bootstrap\Html::img($good->logo,['width'=>'50px'])?></td>
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
            <?PHP echo \yii\bootstrap\Html::a('相册',['goods-gallery/add','id'=>$good->id],$option = ['class'=>'btn btn-success'])?>
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

<?php
echo yii\widgets\LinkPager::widget([
        'pagination'=>$pager,
        'prevPageLabel'=>'上一页',
        'nextPageLabel'=>'下一页',
]);
<table class="table table-bordered table-hover">
    <tr>
        <td colspan="2">
            <?php echo \yii\bootstrap\Html::a('添加', ['goods-gallery/add'], ['class' => 'btn btn-info']) ?>
        </td>
    </tr>
    <?php foreach ($goodsGalleries as $goodsGallery):?>
    <tr>
        <td><?php echo \yii\bootstrap\Html::img($goodsGallery->path,['width'=>'400px'])?></td>
        <td>
            <?php echo \yii\bootstrap\Html::a('删除',['goods-gallery/delete','id'=>$goodsGallery->id],$option = ['class'=>'btn btn-warning'])?>
        </td>
    </tr>
    <?php endforeach;?>
</table>
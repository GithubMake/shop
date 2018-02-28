<table class="table table-borderd table-hover">
    <tr>
        <td>主键</td>
        <td>名称</td>
        <td>简介</td>
        <td>文章分类</td>
        <td>排行</td>
        <td>状态</td>
        <td>创建时间</td>
        <td>操作</td>
    </tr>
    <?php foreach ($articles as $article): ?>
    <tr>
        <td><?php echo $article->id?></td>
        <td><?php echo $article->name?></td>
        <td><?php echo $article->intro?></td>
<!--        <td>--><?php //echo \backend\models\Article::getItems()[$article->article_category_id]?><!--</td>-->
        <td><?php echo \backend\models\ArticleCategory::getNameById($article->article_category_id)?></td>
<!--        <td>--><?php //echo $article->getName()?><!--</td>-->
<!--        <td>--><?php //echo $article->category->name?><!--</td>-->

        <td><?php echo $article->sort?></td>
        <td><?php echo (($article->is_deleted)==0)?'正常':'删除'?></td>
        <td><?php echo date('Y-m-d H:i:s',$article->create_time)?></td>
        <td>
            <?php echo \yii\bootstrap\Html::a('修改',['article/edit','id'=>$article->id],$options=['class'=>'btn btn-danger'])?>
            <?php echo \yii\bootstrap\Html::a('删除',['article/delete','id'=>$article->id],$options=['class'=>'btn btn-warning'])?>
            <?php echo \yii\bootstrap\Html::a('内容',['article-detail/content','id'=>$article->id],$options=['class'=>'btn btn-success'])?>
        </td>
    </tr>
    <?php endforeach; ?>
    <tr>
        <td colspan="8" style="text-align: center">
            <?php echo \yii\bootstrap\Html::a('添加',['article/add'],$options=['class'=>'btn btn-info'])?>
        </td>
    </tr>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
        'pagination'=>$pager,
        'prevPageLabel'=>'上一页',
        'nextPageLabel'=>'下一页',
]);

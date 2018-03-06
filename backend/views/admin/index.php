<?php
/* @var $this yii\web\View */
?>
<table class="table table-border table-hover">
    <tr>
        <td>用户名</td>
        <td>邮箱</td>
        <td>状态</td>
        <td>创建时间</td>
        <td>更新时间</td>
        <td>最后登录时间</td>
        <td>最后登录ip</td>
        <td>操作</td>
    </tr>
    <?php foreach ($admins as $admin):?>
    <tr>
        <td><?php echo $admin->username?></td>
        <td><?php echo $admin->email?></td>
        <td><?php echo (($admin->status)==0)?'未启用':'启用'?></td>
        <td><?php echo date('Y-m-d H:i:s',$admin->created_at)?></td>
        <td><?php echo date('Y-m-d H:i:s',$admin->updated_at)?></td>
        <td><?php echo date('Y-m-d H:i:s',$admin->last_login_time)?></td>
        <td><?php echo $admin->last_login_ip?></td>
        <td>
            <?php echo \yii\bootstrap\Html::a('修改',['admin/edit','id'=>$admin->id],$options=['class'=>'btn btn-danger'])?>
            <?php echo \yii\bootstrap\Html::a('删除',['admin/delete','id'=>$admin->id],$options=['class'=>'btn btn-warning'])?>
        </td>
    </tr>
    <?php endforeach;?>
    <tr>
        <td colspan="10" style="text-align: center">
            <?php echo \yii\bootstrap\Html::a('添加',['admin/add'],$options=['class'=>'btn btn-success'])?>
        </td>
    </tr>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,
    'prevPageLabel'=>'上一页',
    'nextPageLabel'=>'下一页',
]);
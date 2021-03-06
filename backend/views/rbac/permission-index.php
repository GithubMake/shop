<?php
/**
 * @var $this \yii\web\View
 */

$this->registerCssFile('@web/DataTables-1.10.15/media/css/jquery.dataTables.css');
$this->registerJsFile('@web/DataTables-1.10.15/media/js/jquery.js');
$this->registerJsFile('@web/DataTables-1.10.15/media/js/jquery.dataTables.js', ['depends'=>\yii\web\JqueryAsset::class]);//此处应该添加依赖,依赖是写在该插件的js中


$this->registerJs(<<<JS
$(document).ready( function () {
    $('#table').DataTable({
    language: {
        "sProcessing": "处理中...",
        "sLengthMenu": "显示 _MENU_ 项结果",
        "sZeroRecords": "没有匹配结果",
        "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
        "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
        "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
        "sInfoPostFix": "",
        "sSearch": "搜索:",
        "sUrl": "",
        "sEmptyTable": "表中数据为空",
        "sLoadingRecords": "载入中...",
        "sInfoThousands": ",",
        "oPaginate": {
            "sFirst": "首页",
            "sPrevious": "上页",
            "sNext": "下页",
            "sLast": "末页"
        },
        "oAria": {
            "sSortAscending": ": 以升序排列此列",
            "sSortDescending": ": 以降序排列此列"
        }
    }
});
} );
JS
);
?>
<table id="table" class="display">
<!--<table  class="table table-bordered table-hover">-->
    <thead>
    <tr>
        <th>名称</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($permissions as $permission): ?>
        <tr>
            <td><?php echo $permission->name ?></td>
            <td><?php echo $permission->description ?></td>
            <td>
                <?PHP echo \yii\bootstrap\Html::a('修改', ['rbac/edit-permission', 'name' => $permission->name], $option = ['class' => 'btn btn-danger']) ?>
                <?php echo \yii\bootstrap\Html::a('删除', ['rbac/delete-permission', 'name' => $permission->name], $option = ['class' => 'btn btn-warning']) ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>



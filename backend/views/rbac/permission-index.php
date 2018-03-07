<?php
/**
 * @var $this \yii\web\View
 */
//
//$this->registerCssFile('@web/DataTables-1.10.15/media/css/jquery.dataTables.css');
//$this->registerJsFile('@web/DataTables-1.10.15/media/js/jquery.js');
//$this->registerJsFile('@web/DataTables-1.10.15/media/js/jquery.dataTables.js');
//
//
//$this->registerJs(<<<JS
//$(document).ready( function () {
//    $('#table_id_example').DataTable();
//} );
//JS
//);
//?>
<!--<table id="table_id_example" class="display">-->
<table  class="table table-bordered table-hover">
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



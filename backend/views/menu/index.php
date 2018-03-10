<table class="table table-bordered table-hover">
    <thead>
    <tr>
        <th>菜单名称</th>
        <th>路由</th>
        <th>上级分类</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($menuList as $menu): ?>
        <tr data-id="<?php echo $menu->id ?>">
            <td><?php echo $menu->label ?></td>
            <td><?php echo $menu->url ?></td>
            <td><?php echo \backend\models\Menu::getParentIdName($menu->parent_id)?></td>
            <td><?php echo $menu->sort ?></td>
            <td>
                <a href="<?php echo \yii\helpers\Url::to(['menu/edit', 'id' => $menu->id]) ?>" class="btn btn-warning">修改</a>
                <a href="<?php echo \yii\helpers\Url::to(['menu/delete', 'id' => $menu->id]) ?>" class="btn btn-danger">删除</a>
            </td>
        </tr>

    <?php endforeach; ?>
    </tbody>
</table>
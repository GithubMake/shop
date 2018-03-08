<?php
/**
 * 此注释用于告诉编辑器这是视图,然后敲代码的时候有提示
 * @var $this \yii\web\View
 */
$form =  yii\bootstrap\ActiveForm::begin();//form开始
echo $form->field($model,'label')->textInput(['placeholder'=>'菜单名称']);//name
echo $form->field($model,'parent_id')->dropDownList($parentIdLists);//intro
echo $form->field($model,'url')->dropDownList($items);//imgFile
echo $form->field($model,'sort')->textInput(['placeholder'=>'排序']);//sort
echo '<button type="submit" class="btn btn-primary">提交</button>';
\yii\bootstrap\ActiveForm::end();//form结束
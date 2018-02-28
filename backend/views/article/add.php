<?php
$form = \yii\bootstrap\ActiveForm::begin();//form开始
echo $form->field($article,'name')->textInput();//name
echo $form->field($article,'intro')->textarea();//intro
echo $form->field($article,'article_category_id')->dropDownList([
    1=>'文学',
    2=>'科技',
]);//article_category_id
echo $form->field($article,'sort')->textInput();//sort
echo $form->field($articleDetail,'content')->textarea();//content
echo '<button type="submit" class="btn btn-primary">提交</button>';
\yii\bootstrap\ActiveForm::end();//form结束
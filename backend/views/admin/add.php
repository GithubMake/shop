<?php
$form = \yii\bootstrap\ActiveForm::begin();//form开始
echo $form->field($model,'username')->textInput();//username
echo $form->field($model,'password')->passwordInput();//password
echo $form->field($model,'email')->textInput();//email
echo $form->field($model,'status' ,['inline'=>1])->radioList([
    1=>'启用',
    0=>'禁用',
]);//status
echo  $form->field($model,'roles')->checkboxList($items);

echo '<button type="submit" class="btn btn-primary">提交</button>';
\yii\bootstrap\ActiveForm::end();//form结束
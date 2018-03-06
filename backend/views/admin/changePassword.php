<?php
/**
 * @var $this \yii\web\View
 */
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput(['readonly'=>1]);
echo $form->field($model,'oldPassword')->passwordInput();
echo $form->field($model,'newPassword')->passwordInput();
echo $form->field($model,'rePassword')->passwordInput();
echo '<button type="submit" class="btn btn-success">修改</button>';
\yii\bootstrap\ActiveForm::end();
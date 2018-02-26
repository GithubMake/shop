<?php
/**
 * Created by PhpStorm.
 * User: asus-pc
 * Date: 2018/2/26
 * Time: 17:27
 */
$form =  yii\bootstrap\ActiveForm::begin();//form开始
echo $form->field($model,'name')->textInput();//name
echo $form->field($model,'intro')->textarea();//intro
echo $form->field($model,'imgFile')->fileInput();//imgFile
echo $form->field($model,'sort')->textInput();//sort
echo '<button type="submit" class="btn btn-primary">提交</button>';
\yii\bootstrap\ActiveForm::end();//form结束
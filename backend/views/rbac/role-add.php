<?php
/**
 * Created by PhpStorm.
 * User: asus-pc
 * Date: 2018/3/7
 * Time: 16:01
 */
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'description')->textInput();
echo $form->field($model,'permission')->checkboxList($items);
echo '<button type="submit" class="btn btn-primary">提交</button>';
\yii\bootstrap\ActiveForm::end();
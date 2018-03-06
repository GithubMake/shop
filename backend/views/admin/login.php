<?php
/**
 * @var $this \yii\web\View
 */
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
echo $form->field($model,'password')->passwordInput();
echo $form->field($model,'rememberMe')->checkbox();
echo $form->field($model,'code')->widget(\yii\captcha\Captcha::class,[
        'captchaAction'=>'admin/captcha',//用哪个验证码
        'template'=>'<div class="row">
    <div class="col-xs-2">{input}</div>
    <div class="col-xs-4">{image}</div>
    </div> '
    ]
);
echo '<button type="submit" class="btn btn-success">登录</button>';
\yii\bootstrap\ActiveForm::end();
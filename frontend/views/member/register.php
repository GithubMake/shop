<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>用户注册</title>
    <link rel="stylesheet" href="/style/base.css" type="text/css">
    <link rel="stylesheet" href="/style/global.css" type="text/css">
    <link rel="stylesheet" href="/style/header.css" type="text/css">
    <link rel="stylesheet" href="/style/login.css" type="text/css">
    <link rel="stylesheet" href="/style/footer.css" type="text/css">
    <!-- 修改placeholder的颜色-->
    <style>
        input::-webkit-input-placeholder {
            color: #aab2bd;
        }
    </style>
</head>
<body>
<!-- 顶部导航 start -->
<div class="topnav">
    <div class="topnav_bd w990 bc">
        <div class="topnav_left">

        </div>
        <div class="topnav_right fr">
            <ul>
                <li>您好，欢迎来到京西！[<a href="login.html">登录</a>] [<a href="register.html">免费注册</a>]</li>
                <li class="line">|</li>
                <li>我的订单</li>
                <li class="line">|</li>
                <li>客户服务</li>

            </ul>
        </div>
    </div>
</div>
<!-- 顶部导航 end -->

<div style="clear:both;"></div>

<!-- 页面头部 start -->
<div class="header w990 bc mt15">
    <div class="logo w990">
        <h2 class="fl"><a href="index.html"><img src="/images/logo.png" alt="京西商城"></a></h2>
    </div>
</div>
<!-- 页面头部 end -->

<!-- 登录主体部分start -->
<div class="login w990 bc mt10 regist">
    <div class="login_hd">
        <h2>用户注册</h2>
        <b></b>
    </div>
    <div class="login_bd">
        <div class="login_form fl">
            <form id=signupForm action="<?php echo \yii\helpers\Url::to(['member/register']) ?>" method="post">
                <ul>
                    <li>
                        <label for="">用户名：</label>
                        <input type="text" class="txt" name="username" placeholder="3-20位字符，可由中文、字母、数字和下划线组成"/>
                    </li>
                    <li>
                        <label for="">密码：</label>
                        <input type="password" class="txt" name="password" placeholder="6-20位字符，可使用字母、数字和符号的组合"/>
                    </li>
                    <li>
                        <label for="">确认密码：</label>
                        <input id="password" type="password" class="txt" name="confirm_password" placeholder="请再次输入密码"/>
                    </li>
                    <li>
                        <label for="">邮箱：</label>
                        <input type="text" class="txt" name="email" placeholder="邮箱必须合法"/>
                    </li>
                    <li>
                        <label for="">手机号码：</label>
                        <input type="text" class="txt" value="" name="tel" id="tel" placeholder=""/>
                    </li>
                    <li>
                        <label for="">验证码：</label>
                        <input type="text" class="txt" value="" placeholder="请输入短信验证码" name="code" disabled="disabled"
                               id="code"/> <input type="button" onclick="bindPhoneNum(this)" id="get_code" value="获取验证码"
                                                  style="height: 25px;padding:3px 8px"/>

                    </li>
                    <li class="checkcode">
                        <label for="">验证码：</label>
                        <input type="text"  name="captcha" />
                        <img id="img_captcha" alt="" />
                        <span>看不清？<a href="javascript:;" id="change_captcha" >换一张</a></span>
                    </li>

                    <li>
                        <label for="">&nbsp;</label>
                        <input type="checkbox" class="chb" checked="checked" name="agree"/> 我已阅读并同意《用户注册协议》
                    </li>
                    <li>
                        <label for="">&nbsp;</label>
                        <input type="submit" value="" class="login_btn"/>
                    </li>
                </ul>
                <input type="hidden" name="<?php echo Yii::$app->request->csrfParam ?>"
                       value="<?php echo Yii::$app->request->csrfToken ?>"/>
            </form>
        </div>

        <div class="mobile fl">
            <h3>手机快速注册</h3>
            <p>中国大陆手机用户，编辑短信 “<strong>XX</strong>”发送到：</p>
            <p><strong>1069099988</strong></p>
        </div>

    </div>
</div>
<!-- 登录主体部分end -->

<div style="clear:both;"></div>
<!-- 底部版权 start -->
<div class="footer w1210 bc mt15">
    <p class="links">
        <a href="">关于我们</a> |
        <a href="">联系我们</a> |
        <a href="">人才招聘</a> |
        <a href="">商家入驻</a> |
        <a href="">千寻网</a> |
        <a href="">奢侈品网</a> |
        <a href="">广告服务</a> |
        <a href="">移动终端</a> |
        <a href="">友情链接</a> |
        <a href="">销售联盟</a> |
        <a href="">京西论坛</a>
    </p>
    <p class="copyright">
        © 2005-2013 京东网上商城 版权所有，并保留所有权利。 ICP备案证书号:京ICP证070359号
    </p>
    <p class="auth">
        <a href=""><img src="/images/xin.png" alt=""/></a>
        <a href=""><img src="/images/kexin.jpg" alt=""/></a>
        <a href=""><img src="/images/police.jpg" alt=""/></a>
        <a href=""><img src="/images/beian.gif" alt=""/></a>
    </p>
</div>
<!-- 底部版权 end -->

<script src="http://static.runoob.com/assets/jquery-validation-1.14.0/lib/jquery.js"></script>
<script src="http://static.runoob.com/assets/jquery-validation-1.14.0/dist/jquery.validate.min.js">

</script>


<script type="text/javascript">
    function bindPhoneNum() {
        var tel = $('#tel').val();

        $.get("<?php echo \yii\helpers\Url::to(['member/send-message'])?>",{tel:tel});
        //启用输入框
        $('#code').prop('disabled', false);
        var time = 60;
        var interval = setInterval(function () {
            time--;
            if (time <= 0) {
                clearInterval(interval);
                var html = '获取验证码';
                $('#get_code').prop('disabled', false);
            } else {
                var html = time + ' 秒后再次获取';
                $('#get_code').prop('disabled', true);
            }
            $('#get_code').val(html);
        }, 1000);
    }



    $().ready(function () {
// 在键盘按下并释放及提交后验证提交表单
        $("#signupForm").validate({
            rules: {
                username: {
                    required: true,
                    rangelength: [3, 20],
                    //判断用户名是否被占用
                    remote: "<?php echo \yii\helpers\Url::to(['member/validate-username'])?>"
                },
                captcha:"validateCaptcha",
                password: {
                    required: true,
                    rangelength: [6, 20]
                },
                confirm_password: {
                    required: true,
                    rangelength: [6, 20],
                    equalTo: "#password"//密码和确认密码一致
                },
                email: {
                    required: "true",
                    minlength: 4,
                    remote:"<?php echo \yii\helpers\Url::to('member/validate-email')?>"
                },
                tel: {
                    required: true,
                    rangelength: [8, 11],
                    remote:"<?php echo \yii\helpers\Url::to('member/validate-tel')?>"
                },
                code: {
                    required: true,
                    remote: {
                        url: "check-email.php",     //后台处理程序
                        data: {
                            tel: function () {
                                return $("#tel").val();
                            }
                        }
                    },
                    agree: "required"
                }
                },
                messages: {
                    username: {
                        required: "请输入用户名",
                        rangelength: "用户名必须介于3到20个字符",
                        remote: "该用户名已被占用"
                    },
                    password: {
                        required: "请输入密码",
                        rangelength: "密码长度必须介于6到20个字符"
                    },
                    confirm_password: {
                        required: "请输入密码",
                        rangelength: "密码长度必须介于6到20个字符",
                        equalTo: "两次密码输入不一致"
                    },
                    email: {
                        required: "邮箱不能为空",
                        minlength: "邮箱长度不能小于4位",
                        remote: "邮箱已注册"
                    },
                    tel: {
                        required: "电话号码不能为空",
                        rangelength: "格式不正确",
                        remote: "邮箱已注册"
                    },
                    code: {
                        required: "手机验证码不能为空",
                        remote: "手机验证码不正确"
                    },
                    captcha:"动态验证码不正确",

                    agree: "请接受我们的声明"
                },
                errorElement: 'span'
            })
    });


    $("#change_captcha").click(function () {
        //获取新验证码图片的url
        $.getJSON('<?=\yii\helpers\Url::to(['site/captcha','refresh'=>1])?>',function(data){
            //将新验证码图片的地址更新到原验证码图片
            $("#img_captcha").attr('src',data.url);
            //保存
            $('body').data('captcha',data.hash1);
        });
    });

    $("#change_captcha").click();

    //自定义验证方法
    jQuery.validator.addMethod("validateCaptcha", function(value, element) {
        var v = value.toLowerCase();
        for (var i = v.length - 1, h = 0; i >= 0; --i) {
            h += v.charCodeAt(i);
        }
        var hash = $('body').data('captcha');
        return h==hash;//返回验证结果
    }, "验证码错误");
</script>
</body>
</html>
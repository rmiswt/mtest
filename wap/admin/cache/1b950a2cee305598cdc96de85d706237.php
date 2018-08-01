<?php if(!defined('INVIEW'))die('LAJI'); ?><!DOCTYPE html>
<html>
  
  <head>
    <meta charset="UTF-8">
    <title>海华金融后台管理系统</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta content="width=device-width, initial-scale=0.8, maximum-scale=0.8, user-scalable=0;" name="viewport"/>
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="<?php echo __PUBLIC__?>css/font.css">
    <link rel="stylesheet" href="<?php echo __PUBLIC__?>css/hhjr.css">
    <script type="text/javascript" src="<?php echo __PUBLIC__?>js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="<?php echo __PUBLIC__?>lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?php echo __PUBLIC__?>js/hhjr.js"></script>
    <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
    <!--[if lt IE 9]>
      <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
      <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

<body class="login-bg">
    
    <div class="login layui-anim layui-anim-up">
        <div class="message">x-admin2.0-管理登录</div>
        <div id="darkbannerwrap"></div>
        
        <form class="layui-form" id="form">
            <input name="username" placeholder="用户名"  type="text" lay-verify="required" class="layui-input" id="name">
            <hr class="hr15">
            <input name="password" lay-verify="required" placeholder="密码"  type="password" class="layui-input" id="pwd">
            <hr class="hr15">
            <input value="登录" lay-submit lay-filter="login" style="width:100%;" type="button" id="login">
            <hr class="hr20" >
        </form>
    </div>

    <script>
        $(()=>{
            $("#login").click(function(){
                var name = $('#name').val();
                var pwd = $('#pwd').val();
                if (name == ''){
                    layer.msg('用户名不能为空!', {icon: 2});
                    return false;
                }
                if (pwd == ''){
                    layer.msg('密码不能为空!', {icon: 2});
                    return false;
                }
                $.post('/admin/auth/login.html',$('#form').serialize(),function(res){
                    result= JSON.parse(res)
                    if(result.status == '1'){
                        layer.msg(result.msg,{icon: 2});
                        return false;
                    }else {
                        location.href = '/admin/index/index.html';
                    }
                })
            })
        })
    </script>

    
    <!-- 底部结束 -->

</body>
</html>
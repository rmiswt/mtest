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

    <body>
    <div class="x-body layui-anim layui-anim-up">
        <blockquote class="layui-elem-quote">欢迎管理员：
            <span class="x-red"><?php echo $_SESSION['userinfo']['username']; ?></span>！当前时间:<?php echo date('Y-m-d H:i'); ?></blockquote>
        
        <fieldset class="layui-elem-field">
            <legend>开发团队</legend>
            <div class="layui-field-box">
                <table class="layui-table">
                    <tbody>
                        <tr>
                            <th>版权所有</th>
                            <td>海华金融超市
                                <a href="http://www.fjhhjr.com/" class='x-a' target="_blank">访问官网</a></td>
                        </tr>
                        <tr>
                            <th>开发者</th>
                            <td>王涛(821586178@qq.com)</td></tr>
                    </tbody>
                </table>
            </div>
        </fieldset>
    </div>
    
    </body>
</html>
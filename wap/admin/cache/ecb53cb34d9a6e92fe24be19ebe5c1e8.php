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
    <div class="x-body">
        <form class="layui-form" id="formUser">
          <div class="layui-form-item">
              <label for="username" class="layui-form-label">
                  <span class="x-red">*</span>登录名
              </label>
              <div class="layui-input-inline">
                  <input type="text" id="username" name="username" required="" lay-verify="required"
                  autocomplete="off" class="layui-input">
              </div>
              <div class="layui-form-mid layui-word-aux">
                  <span class="x-red">*</span>将会成为您唯一的登入名
              </div>
          </div>
          <div class="layui-form-item">
              <label class="layui-form-label"><span class="x-red">*</span>角色</label>
              <div class="layui-input-block">
                <?php if(count($roles)>0):foreach($roles as $role): ?>
                <input type="radio" name="role_id" lay-skin="primary" title="<?php echo $role['name']; ?>"  value="<?php echo $role['id']; ?>">
               <?php endforeach;endif; ?> 
              </div>
          </div>
          <div class="layui-form-item">
              <label for="L_pass" class="layui-form-label">
                  <span class="x-red">*</span>密码
              </label>
              <div class="layui-input-inline">
                  <input type="password" id="L_pass" name="pwd" required="" lay-verify="pwd"
                  autocomplete="off" class="layui-input">
              </div>
              <div class="layui-form-mid layui-word-aux">
                  6到16个字符
              </div>
          </div>
          <div class="layui-form-item">
              <label for="L_repass" class="layui-form-label">
                  <span class="x-red">*</span>确认密码
              </label>
              <div class="layui-input-inline">
                  <input type="password" id="L_repass"  required="" lay-verify="repass"
                  autocomplete="off" class="layui-input">
              </div>
          </div>
          <div class="layui-form-item">
              <label for="L_repass" class="layui-form-label">
              </label>
              <button  class="layui-btn" lay-filter="add" lay-submit="">
                  增加
              </button>
          </div>
      </form>
    </div>
    <script>
        layui.use(['form','layer'], function(){
            $ = layui.jquery;
          var form = layui.form
          ,layer = layui.layer;
        
          //自定义验证规则
          form.verify({
            nikename: function(value){
              if(value.length < 5){
                return '昵称至少得5个字符啊';
              }
            }
            ,pwd: [/(.+){6,12}$/, '密码必须6到12位']
            ,repass: function(value){
                if($('#L_pass').val()!=$('#L_repass').val()){
                    return '两次密码不一致';
                }
            }
          });

          //监听提交
          form.on('submit(add)', function(data){
            console.log(data);
            var form = $('#formUser').serialize();
            //发异步，把数据提交给php
            $.post('/admin/admin/add.html',form,function(result){
                var res = JSON.parse(result);
                if (res.status == 0) {
                    layer.alert("增加成功", {icon: 6},function () {
                        // 获得frame索引
                        var index = parent.layer.getFrameIndex(window.name);
                        //关闭当前frame
                        parent.layer.close(index);
                    });
                }else {
                    layer.alert(result.msg, {icon: 5});
                }
            })
            return false;
          });
          
          
        });
    </script>

  </body>

</html>
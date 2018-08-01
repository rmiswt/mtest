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
    <div class="x-nav">
      <span class="layui-breadcrumb">
        <a href="">首页</a>
        <a href="">演示</a>
        <a>
          <cite>导航元素88</cite></a>
      </span>
      <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
    </div>
    <div class="x-body">
      <div class="layui-row">
        <form id="formadd" class="layui-form layui-col-md12 x-so layui-form-pane">
          <div class="layui-input-inline">
            <select name="module_id">
            <?php if(count($modules)>0):foreach($modules as $module): ?>
              <option value="<?php echo $module['id']; ?>"><?php echo $module['name']; ?></option>
             <?php endforeach;endif; ?>
            </select>
          </div>
          <div class="layui-input-inline">
            <input class="layui-input" placeholder="控制器行为" name="rule" id="rule" >
          </div>
          <div class="layui-input-inline">
            <input class="layui-input" placeholder="显示顺序" name="display_order"  value="0">
          </div>
          <div class="layui-input-inline">
            <select name="type">
              <option value="0">菜单</option>
              <option value="1">权限</option>
            </select>
          </div>
          <input class="layui-input" placeholder="权限名" name="name"  id="name">
        </form>
      </div>
      <xblock>
          <button class="layui-btn" id="roleadd" ><i class="layui-icon"></i>增加</button>
        <!-- <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button> -->
        <span class="x-right" style="line-height:40px">共有数据：88 条</span>
      </xblock>
      <table class="layui-table">
        <thead>
          <tr>
            <th>
              <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
            </th>
            <th>ID</th>
            <th>权限规则</th>
            <th>权限名称</th>
            <th>所属分类</th>
            <th>所属类型</th>
            <th>操作</th>
        </thead>
        <tbody>
        <?php if(count($rules)>0):foreach($rules as $rule): ?>
          <tr>
            <td>
              <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='<?php echo $rule['id']; ?>'><i class="layui-icon">&#xe605;</i></div>
            </td>
            <td><?php echo $rule['id']; ?></td>
            <td><?php echo $rule['rule']; ?></td>
            <td><?php echo $rule['name']; ?></td>
            <td><?php echo $rule['modulename']; ?></td>
            <td><?php if($rule['type']==1): ?>权限<?php else: ?>菜单<?php endif; ?></td>
            <td class="td-manage">
              <a title="编辑"  onclick="x_admin_show('编辑','/admin/admin/ruleedit.html?id=<?php echo $rule['id']; ?>')" href="javascript:;">
                <i class="layui-icon">&#xe642;</i>
              </a>
              <a title="删除" onclick="member_del(this,'<?php echo $rule['id']; ?>')" href="javascript:;">
                <i class="layui-icon">&#xe640;</i>
              </a>
            </td>
          </tr>
          <?php endforeach;endif; ?>
        </tbody>
      </table>
      <div class="page">
        <div>
          <a class="prev" href="">&lt;&lt;</a>
          <a class="num" href="">1</a>
          <span class="current">2</span>
          <a class="num" href="">3</a>
          <a class="num" href="">489</a>
          <a class="next" href="">&gt;&gt;</a>
        </div>
      </div>

    </div>
    <script>
      layui.use('laydate', function(){
        var laydate = layui.laydate;

        
        
        //执行一个laydate实例
        laydate.render({
          elem: '#start' //指定元素
        });

        //执行一个laydate实例
        laydate.render({
          elem: '#end' //指定元素
        });
      });

       /*用户-停用*/
      function member_stop(obj,id){
          layer.confirm('确认要停用吗？',function(index){

              if($(obj).attr('title')=='启用'){

                //发异步把用户状态进行更改
                $(obj).attr('title','停用')
                $(obj).find('i').html('&#xe62f;');

                $(obj).parents("tr").find(".td-status").find('span').addClass('layui-btn-disabled').html('已停用');
                layer.msg('已停用!',{icon: 5,time:1000});

              }else{
                $(obj).attr('title','启用')
                $(obj).find('i').html('&#xe601;');

                $(obj).parents("tr").find(".td-status").find('span').removeClass('layui-btn-disabled').html('已启用');
                layer.msg('已启用!',{icon: 5,time:1000});
              }
              
          });
      }

      /*用户-删除*/
      function member_del(obj,id){
          layer.confirm('确认要删除吗？',function(index){
              //发异步删除数据
              $.post('/admin/admin/ruledel.html',{id:id},function(result){
                result = JSON.parse(result)
                if (result.status==0) {
                  $(obj).parents("tr").remove();
                  layer.msg('已删除!',{icon:1,time:1000});
                }
              })  
          });
      }

      $('#roleadd').click(function(){
        var controller = $('#controller').val();
        var action = $('#action').val();
        var name = $('#name').val();
        if (controller == ''){
            layer.msg('控制器不能为空!', {icon: 2});
            return false;
        }
        if (action == ''){
            layer.msg('行为不能为空!', {icon: 2});
            return false;
        }
        if (name == ''){
            layer.msg('权限名不能为空!', {icon: 2});
            return false;
        }
        var data = $('#formadd').serialize();
        $.post("/admin/admin/ruleadd.html",data,function(result){
          var res = JSON.parse(result);
          if (res.status == 0) {
            layer.msg(res.msg, {icon: 2});
            setTimeout(()=>{
              window.location.reload();
            },1000)
          }
        });
      })

      function delAll (argument) {

        var data = tableCheck.getData();
  
        layer.confirm('确认要删除吗？'+data,function(index){
            //捉到所有被选中的，发异步进行删除
            layer.msg('删除成功', {icon: 1});
            $(".layui-form-checked").not('.header').parents('tr').remove();
        });
      }
    </script>

  </body>

</html>
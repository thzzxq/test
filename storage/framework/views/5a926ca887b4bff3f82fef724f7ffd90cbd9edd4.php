<?php $__env->startSection('admin-css'); ?>
    <link href="<?php echo e(asset('asset_admin/assets/plugins/gritter/css/jquery.gritter.css')); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo e(asset('asset_admin/assets/plugins/bootstrap-sweetalert-master/dist/sweetalert.css')); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo e(asset('asset_admin/assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('asset_admin/assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('asset_admin/assets/plugins/parsley/src/parsley.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('asset_admin/assets/plugins/bootstrap-datetimepicker/css/datetimepicker.css')); ?>" rel="stylesheet" type="text/css">


<?php $__env->stopSection(); ?>

<?php $__env->startSection('admin-content'); ?>
    <div id="content" class="content">
        <!-- begin breadcrumb -->
        <ol class="breadcrumb pull-right">
            <li><a href="javascript:;">Home</a></li>
            <li><a href="javascript:;">Tables</a></li>
            <li class="active">Basic Tables</li>
        </ol>
           <input  onclick="setSelectAll();" id="subcheck" name="permission[]" value="" type="checkbox" >

        <!-- end breadcrumb -->
        <!-- begin page-header -->
        <h1 class="page-header">文章列表 <small>header small text goes here...</small></h1>
        <!-- end page-header -->
        <!-- begin row -->
        <div class="row">
            <!-- begin col-6 -->
            <div class="col-md-12">
                <!-- begin panel -->
                <div class="panel panel-inverse" data-sortable-id="table-basic-5">
                    <div class="panel-heading">
                        <div class="panel-heading-btn">
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                        </div>
                        <h4 class="panel-title">列表</h4>
                    </div>
                    <div class="panel-body">
                    <div class="col-md-1">
                           <a href="#modal-new" data-toggle="modal">
                          <button type="button" class="btn btn-primary m-r-5 m-b-5" style="height: 33px;" ><i class="fa fa-plus-square-o"></i> 新增</button>
                         </a>
                    </div>
                   <!--   <div class="col-md-1">
                            <select class="form-control" id="ajax-submit" >
                                <option value="0">批量操作</option>
                                    <option value="verify">批量审核</option>
                                    <option value="unverify">批量撤销</option>
                                    <option value="delete">批量删除</option>
                            </select>
                        </div> -->
                        <div class="col-md-2">
                            <div class="input-group input-daterange">
                                <input type="text" class="form-control" id="time_start" name="start" placeholder="开始时间" style="width: 100px;">
                                <span class="input-group-addon">至</span>
                                <input type="text" class="form-control" id="time_end" name="end" placeholder="截止时间" style="width: 100px;">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <a href="javascript:void(0)" id="selectTime" class="btn btn-sm btn-success" style="height: 33px;">时间搜索</a>
                        </div>
                        <div class="clearfix"></div>
                        <hr>

                        <table class="table table-bordered table-hover" id="datatable">
                            <thead>
                            <tr>
                                <th style="width: 22%;">文章标题</th>
                                <th style="width: 22%;">文章内容</th>
                                <th style="width: 23%;">展示名字</th>
                                <th style="width: 23%;">更新时间</th>
                                <th style="width: 10%;">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($articles as $article): ?>
                            <tr>
                                <td><?php echo e($article['title']); ?></td>
                                <td><?php echo e($article['content']); ?></td>
                                <td><?php echo e($article['display_name']); ?></td>
                                <td><?php echo e($article['updated_at']); ?></td>
                                <td>
                                   <a href="#modal-edit"  data-toggle="modal" onclick="ajaxEdit('');">
                                <button type="button" class='btn btn-success btn-xs'><i class='fa fa-pencil'> 编辑</i></button>
                                   </a>
                                  <a href='javascript:;' data-id='9' class='btn btn-danger btn-xs destroy'>
                                  <i class='fa fa-pencil'> 删除</i>
                                   </a>


                                </td>
                            </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- end panel -->
            </div>
            <!-- end col-6 -->
        </div>
        <!-- end row -->
    </div>



    <div class="modal fade" id="modal-new">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">添加文章</h4>
            </div>
            <form action="<?php echo e(url('admin/article')); ?>" method="post" id="form_new" enctype="multipart/form-data" >
            <!--  <?php echo e(csrf_field()); ?> -->
                <div class="modal-body clearfix">

                       <div class="form-group floating-label">
                        <label for="regular2">文章标题 <span style="color:red;font-size:18px" >*</span></label>
                        <input name="title" type="text" class="form-control"   placeholder="文章标题" data-parsley-validate="true" data-parsley-required="true"  data-parsley-required-message="请输入文章标题" >
                      </div>

                    <div class="form-group floating-label">
                        <label for="textarea2">文章提示</label>
                        <textarea name="tips" id="textarea2" class="form-control" rows="3" placeholder="文章标题" data-parsley-required="true" data-parsley-type="number" data-parsley-required-message="请输入文章标题"></textarea>

                    </div>

                        <div class="form-group floating-label" >
                            <label for="regular2">文章详情<span style="color:red;font-size:18px" >*</span></label>
                            <textarea name="text" id="textarea2" class="form-control" rows="8"  placeholder="文章详情" data-parsley-required="true" data-parsley-required-message="请输入文章详情"></textarea>
                        </div>
                    <div class="row">

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class=" control-label">是否可用</label>
                                <div class="">
                                    <label class="radio-inline radio-styled">
                                        <input checked="true" type="radio" name="enable" value="1"><span>是</span>
                                    </label>
                                    <label class="radio-inline radio-styled">
                                        <input type="radio" name="enable" value="0"><span>否</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                       </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn-raised ink-reaction btn-danger btn" data-dismiss="modal">关闭</a>
                    <!-- <a href="javascript: $('#form_new').submit()" class="btn btn-raised ink-reaction btn-primary">提交</a> -->
                      <button type="submit" class="btn btn-raised ink-reaction btn-primary">提交</button>
                </div>
            </form>
        </div>
    </div>
</div>


  <div class="modal fade" id="modal-edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">编辑文章</h4>
            </div>
            <form action="<?php echo e(url('admin/article/5')); ?>" method="post" id="form_new" enctype="multipart/form-data" data-parsley-validate="true">
             <?php echo e(method_field('PATCH')); ?>

                <div class="modal-body clearfix">



                       <div class="form-group floating-label">
                        <label for="regular2">文章标题 <span style="color:red;font-size:18px" >*</span></label>
                        <input name="title" type="text" class="form-control"   placeholder="文章标题" data-parsley-required="true"  data-parsley-required-message="请输入文章标题" >
                      </div>


                    <div class="form-group floating-label">
                        <label for="textarea2">文章提示</label>
                        <textarea name="tips" id="textarea2" class="form-control" rows="3" placeholder="文章标题" data-parsley-required="true" data-parsley-type="number" data-parsley-required-message="请输入文章标题"></textarea>

                    </div>

                        <div class="form-group floating-label" >
                            <label for="regular2">文章详情<span style="color:red;font-size:18px" >*</span></label>
                            <textarea name="text" id="textarea2" class="form-control" rows="8"  placeholder="文章详情" data-parsley-required="true" data-parsley-required-message="请输入文章详情"></textarea>
                        </div>
                    <div class="row">

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class=" control-label">是否可用</label>
                                <div class="">
                                    <label class="radio-inline radio-styled">
                                        <input checked="true" type="radio" name="enable" value="1"><span>是</span>
                                    </label>
                                    <label class="radio-inline radio-styled">
                                        <input type="radio" name="enable" value="0"><span>否</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                       </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn-raised ink-reaction btn-danger btn" data-dismiss="modal">关闭</a>
                    <!-- <a href="javascript: $('#form_new').submit()" class="btn btn-raised ink-reaction btn-primary">提交</a> -->
                      <button type="submit" class="btn btn-raised ink-reaction btn-primary">提交</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('admin-js'); ?>
    <script src="<?php echo e(asset('asset_admin/assets/plugins/gritter/js/jquery.gritter.js')); ?>"></script>
    <script src="<?php echo e(asset('asset_admin/assets/plugins/bootstrap-sweetalert-master/dist/sweetalert.js')); ?>"></script>
    <script src="<?php echo e(asset('asset_admin/assets/plugins/DataTables/media/js/jquery.dataTables.js')); ?>"></script>
    <script src="<?php echo e(asset('asset_admin/assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('asset_admin/assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js')); ?>"></script>
    <script src="<?php echo e(asset('asset_admin/assets/plugins/parsley/dist/parsley.js')); ?>"></script>
    <script src="<?php echo e(asset('asset_admin/js/article.list.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('asset_admin/assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('asset_admin/assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('asset_admin/assets/plugins/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js')); ?>" charset="UTF-8"></script>
    <script>
        $(function(){
            <?php if(session()->has('flash_notification.message')): ?>
                //通知信息
                $.gritter.add({
                    title: '操作消息！',
                    text: '<?php echo session('flash_notification.message'); ?>'
                });
            <?php endif; ?>

            //删除
            $(document).on('click','.destroy',function(){
                var _delete_id = $(this).attr('data-id');
                swal({
                        title: "确定删除？",
                        text: "删除将不可逆，请谨慎操作！",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger",
                        cancelButtonText: "取消",
                        confirmButtonText: "确定",
                        closeOnConfirm: false
                    },
                    function () {
                        $('form[name=delete_item_'+_delete_id+']').submit();
                    }
                );
            });
        });

        $("#selectTime").click(function () {
        var time_start = $("#time_start").val();
            time_end   = $("#time_end").val();
            column_id  = $("#column_id").val();

        if (time_start.length=='' && time_end.length==''){
            alert('请选择时间');
        } else {
            location.href='/website/module/index?type=article&column_id=' + column_id + '&time_start=' + time_start + '&time_end=' + time_end;
        }
    });




           // 时间控件
        $('#time_start').datetimepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,

            minView: 3,
            language: "zh-CN"
        });
        $('#time_end').datetimepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,

            minView: 3,
            language: "zh-CN"
        });
    </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
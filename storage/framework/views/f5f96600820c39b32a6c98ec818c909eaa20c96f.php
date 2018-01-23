<?php $__env->startSection('admin-css'); ?>
<link href="<?php echo e(asset('asset_admin/assets/plugins/nestable/nestable.css')); ?>" rel="stylesheet" type="text/css">
 <link href="<?php echo e(asset('asset_admin/assets/plugins/gritter/css/jquery.gritter.css')); ?>" rel="stylesheet" type="text/css">
  <link href="<?php echo e(asset('asset_admin/assets/plugins/parsley/src/parsley.css')); ?>" rel="stylesheet" />
  <link href="<?php echo e(asset('asset_admin/assets/plugins/bootstrap-sweetalert-master/dist/sweetalert.css')); ?>" rel="stylesheet" type="text/css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('admin-content'); ?>


<div id="content" class="content">
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li>
            <a href="javascript:;">Home</a>
        </li>
        <li>
            <a href="javascript:;">Form Stuff</a>
        </li>
        <li class="active">Form Validation</li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->

    <h1 class="page-header">
        分类列表
        <small>header small text goes here...</small>
    </h1>
    <!-- end page-header -->

    <!-- begin row -->
    <div class="row">

        <div class="col-md-12">
            <!-- begin panel -->
            <div class="panel panel-inverse" data-sortable-id="form-validation-1">
                <div class="panel-heading">
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"> <i class="fa fa-expand"></i>
                        </a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"> <i class="fa fa-repeat"></i>
                        </a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse">
                            <i class="fa fa-minus"></i>
                        </a>

                    </div>
                    <h4 class="panel-title">Basic Form Validation</h4>
                </div>
                <?php if(count($errors)>0): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach($errors->all() as $error): ?>
                        <li><?php echo e($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
                <div class="panel-body panel-form">

                    <div class="panel-body">

                    <menu id="nestable-menu">
                        <a href="<?php echo e(route('category.create')); ?>">
                            <a href="#modal-new" class="btn btn-sm btn-raised ink-reaction btn-primary" data-toggle="modal">添加渠道</a>
                        </a>
                        <button type="button" class="btn btn-sm btn-info  btn-raised ink-reaction" data-action="expand-all">全部展开</button>
                        <button type="button" class="btn btn-sm  btn-success btn-raised ink-reaction" data-action="collapse-all">全部关闭</button>
                    </menu>
                      <div class="cf nestable-lists">
                        <div class="dd" id="nestable">
                            <ol class="dd-list">
                                <?php echo $list; ?>

                            </ol>
                        </div>
                    </div>

                    </div>
                 </div>
                </div>
            </div>
        </div>
    </div>



 <div class="modal fade" id="modal-new">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">添加分类</h4>
            </div>

             <form class="form" role="form" method="post" action="<?php echo e(route('category.store')); ?>" id="form_new" enctype="multipart/form-data" data-parsley-validate="true" >
             <div class="modal-body clearfix">
                    <?php echo e(csrf_field()); ?>

                    <div class="form-group floating-label">
                        <input name="title" type="text" class="form-control" placeholder="分类标题" data-parsley-required="true"  data-parsley-required-message="请输入分类标题">
                         <input value="1" name="isopen" type="hidden" >
                        <label for="regular2">分类名</label>
                    </div>
                    <div class="form-group floating-label">
                        <input value="0" name="order" type="text" class="form-control" data-parsley-required="true" data-parsley-required-message="请输入排序大小">
                        <label for="regular2">排序</label>
                    </div>
                    <div class="form-group floating-label">
                        <select id="select2" name="parent_id" class="form-control">
                            <option value="0">顶级分类</option>
                            <?php foreach($cates as $cate): ?>
                                <option value="<?php echo e($cate['id']); ?>"><?php echo e($cate['title']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <label for="select2">上级分类</label>
                    </div>
                    <div class="form-group floating-label">
                        <button class="btn btn-raised ink-reaction btn-primary" type="submit">保存</button>
                        <a href="<?php echo e(route('category.index')); ?>"><button class="btn-raised ink-reaction btn-danger btn" type="button">返回</button></a>
                    </div>
                </div>
                </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('admin-js'); ?>
 <script src="<?php echo e(asset('asset_admin/assets/plugins/nestable/jquery.nestable.js')); ?>"></script>
 <script src="<?php echo e(asset('asset_admin/assets/plugins/gritter/js/jquery.gritter.js')); ?>"></script>
 <script src="<?php echo e(asset('asset_admin/assets/plugins/parsley/dist/parsley.js')); ?>"></script>
 <script src="<?php echo e(asset('asset_admin/assets/plugins/bootstrap-sweetalert-master/dist/sweetalert.js')); ?>"></script>
 <script>

        $(document).ready(function()
        {
            $('.dd').nestable({/* config options */});
            var updateOutput = function(e)
            {

                var list   = e.length ? e : $(e.target),
                    output = list.data('output');
                if (window.JSON) {
                    output.val(window.JSON.stringify($('.dd').nestable('serialize')));//, null, 2));
                    <?php /*$.post('<?php echo e(route('category.order')); ?>',{*/ ?>
                        <?php /*order:JSON.stringify($('.dd').nestable('serialize')),*/ ?>
                        <?php /*_token: '<?php echo e(csrf_token()); ?>',*/ ?>
                    <?php /*},function (data) {*/ ?>
                        <?php /*toastr.success("成功排序");*/ ?>
                    <?php /*});*/ ?>
                } else {
                    toastr.error("获取数据失败");
                }
            };
            $('#nestable').nestable({
                group: 1
            })
                .on('change', updateOutput);

            updateOutput($('#nestable').data('output', $('#nestable-output')));

            $('#nestable-menu').on('click', function(e)
            {
                // alert(1);
                var target = $(e.target),
                    action = target.data('action');
                if (action === 'expand-all') {
                    $('.dd').nestable('expandAll');
                }
                if (action === 'collapse-all') {
                    $('.dd').nestable('collapseAll');
                }
                $('.dd').on('change', function (e) {
                    toastr.success("成功排序");
                });

                $('.dd').on('change', function() {

                });
            });

        });
 </script>

<script>
 $(function(){
            <?php if(session()->has('flash_notification.message')): ?>
                //通知信息
                $.gritter.add({
                    title: '操作消息！',
                    text: '<?php echo session('flash_notification.message'); ?>'
                });
            <?php endif; ?>

            // 删除
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
                     location.href='/admin/category/'+_delete_id+'/delete';
                    }
                );
            });
        });


   // function thz(k){

   //              $.gritter.add({
   //                  title: '操作消息！',
   //                  text: '操出来！！！',
   //              });
   //          }
  </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
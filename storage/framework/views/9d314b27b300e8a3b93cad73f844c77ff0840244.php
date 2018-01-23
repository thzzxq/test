<?php $__env->startSection('admin-css'); ?>
    <link href="<?php echo e(asset('asset_admin/assets/plugins/parsley/src/parsley.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('asset_admin/assets/plugins/bootstrap-select/bootstrap-select.min.css')); ?>" rel="stylesheet" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('admin-content'); ?>
    <div id="content" class="content">
        <!-- begin breadcrumb -->
        <ol class="breadcrumb pull-right">
            <li><a href="javascript:;">Home</a></li>
            <li><a href="javascript:;">Form Stuff</a></li>
            <li class="active">Form Validation</li>
        </ol>
        <!-- end breadcrumb -->
        <!-- begin page-header -->
        <h1 class="page-header">新增后台用户 <small>header small text goes here...</small></h1>
        <!-- end page-header -->

        <!-- begin row -->
        <div class="row">
            <!-- begin col-6 -->
            <div class="col-md-12">
                <!-- begin panel -->
                <div class="panel panel-inverse" data-sortable-id="form-validation-1">
                    <div class="panel-heading">
                        <div class="panel-heading-btn">
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
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
                        <form class="form-horizontal form-bordered" data-parsley-validate="true" action="<?php echo e(url('admin/adminuser')); ?>" method="POST">
                            <?php echo e(csrf_field()); ?>

                            <!-- <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="email">邮箱 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <input class="form-control" type="text" name="email" placeholder="邮箱（将会作为登录名）" data-parsley-required="true" data-parsley-required-message="请输入邮箱" value="<?php echo e(old('email')); ?>"/>
                                </div>
                            </div> -->
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="name">姓名 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <input class="form-control" type="text" name="name" placeholder="姓名（将会作为登录名）" data-parsley-length="[2,20]" data-parsley-length-message="姓名长度2~20字符" data-parsley-required="true" data-parsley-required-message="请输入姓名" value="<?php echo e(old('name')); ?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="password">密码 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <input class="form-control" id="password" type="text" name="password" placeholder="密码" data-parsley-length="[6,12]" data-parsley-length-message="密码长度6~12字符" data-parsley-required="true" data-parsley-required-message="请输入密码" value="<?php echo e(old('password')); ?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="password">确认密码 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <input class="form-control" type="text" name="password_confirmation" placeholder="确认密码" data-parsley-length="[6,12]" data-parsley-length-message="密码长度6~12字符" data-parsley-required="true" data-parsley-required-message="请确认密码" data-parsley-equalto="#password" data-parsley-equalto-message="两次密码输入不一致" value="<?php echo e(old('password_confirmation')); ?>"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4"></label>
                                <div class="col-md-6 col-sm-6">
                                    <button type="submit" class="btn btn-primary">提交</button>
                                     <a href="#" onClick="javascript :history.back(-1);"  type="submit" class="btn btn-danger">返回</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- end panel -->
            </div>
            <!-- end col-6 -->
        </div>
        <!-- end row -->
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('admin-js'); ?>
    <script src="<?php echo e(asset('asset_admin/assets/plugins/parsley/dist/parsley.js')); ?>"></script>
    <script src="<?php echo e(asset('asset_admin/assets/plugins/bootstrap-select/bootstrap-select.min.js')); ?>"></script>
    <script>
        $('.selectpicker').selectpicker('render');
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
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
        <h1 class="page-header">Form Validation <small>header small text goes here...</small></h1>
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
                        <form class="form-horizontal form-bordered" data-parsley-validate="true" action="<?php echo e(url('admin/menus')); ?>" method="POST">
                            <?php echo e(csrf_field()); ?>

                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="name">菜单名称 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <input class="form-control" type="text" name="name" placeholder="菜单名称" data-parsley-required="true" data-parsley-required-message="请输入菜单名称" value="<?php echo e(old('name')); ?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="url">菜单链接 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <input class="form-control" type="text" name="url" placeholder="菜单链接" data-parsley-required="true" data-parsley-required-message="请输入菜单链接" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="slug">权限名称 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <input class="form-control" type="text" name="slug" placeholder="权限名称" data-parsley-required="true" data-parsley-required-message="请输入权限名称" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="icon">菜单图标 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <input class="form-control" type="text" name="icon" placeholder="菜单图标" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="icon">上级菜单 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <select class="form-control selectpicker"
                                            data-live-search="true"
                                            data-style="btn-white"
                                            data-parsley-required="true"
                                            data-parsley-errors-container="#parent_id_error"
                                            data-parsley-required-message="请选择上级菜单"
                                            name="parent_id">
                                        <option value="">-- 请选择 --</option>
                                        <option value="0">顶级菜单</option>
                                        <?php foreach($topMenus as $menu): ?>
                                        <option value="<?php echo e($menu->id); ?>"><?php echo e($menu->name); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <p id="parent_id_error"></p>
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
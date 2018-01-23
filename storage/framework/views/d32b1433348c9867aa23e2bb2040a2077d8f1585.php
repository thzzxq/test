<?php /*echo '<pre>';print_r($adminMenus);*/?>
<div id="sidebar" class="sidebar sidebar-transparent">
    <!-- begin sidebar scrollbar -->
    <div data-scrollbar="true" data-height="100%">
        <!-- begin sidebar user -->
        <ul class="nav">
            <li class="nav-profile">
                <div class="image">
                    <a href="javascript:;"><img src="<?php echo e(asset('asset_admin/assets/img/user-13.jpg')); ?>" alt="" /></a>
                </div>
                <div class="info">
                    <?php echo e(auth('admin')->user()->name); ?>

                    <small><?php echo e(auth('admin')->user()->email); ?></small>
                </div>
            </li>
        </ul>
        <!-- end sidebar user -->
        <!-- begin sidebar nav -->
        <ul class="nav">
            <?php foreach($adminMenus as $adminMenu): ?>

            <li class="has-sub <?php if($adminMenu['url'] == Request::path()): ?> active <?php endif; ?>">
                <a href="<?php echo e(url($adminMenu['url'])); ?>">
                    <?php if(isset($adminMenu['child'])): ?>
                    <b class="caret pull-right"></b>
                    <?php endif; ?>

                    <?php if(!empty($adminMenu['icon'])): ?>
                        <i class="<?php echo e($adminMenu['icon']); ?>"></i>
                    <?php endif; ?>
                    <span><?php echo e($adminMenu['name']); ?></span>
                </a>
                <?php if(isset($adminMenu['child'])): ?>
                <ul class="sub-menu">
                    <?php foreach($adminMenu['child'] as $menus): ?>

                            <li class="has-sub <?php if($menus['url'] == Request::path()): ?> active <?php endif; ?>">
                                <a href="<?php echo e(url($menus['url'])); ?>">
                                    <?php if(isset($menus['child'])): ?>
                                        <b class="caret pull-right"></b>
                                    <?php endif; ?>
                                    <?php echo e($menus['name']); ?>

                                </a>
                            </li>

                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </li>

            <?php endforeach; ?>
            <!-- begin sidebar minify button -->
            <li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>
            <!-- end sidebar minify button -->
        </ul>
        <!-- end sidebar nav -->
    </div>
    <!-- end sidebar scrollbar -->
</div>
<script>
    var activeNode = $('.active');
    activeNode.parents('li').addClass('active');
</script>
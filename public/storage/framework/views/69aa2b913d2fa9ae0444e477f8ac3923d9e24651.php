<?php $__currentLoopData = $widgets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $widgetAlias => $widget): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php echo $this->makePartial('widget_item', [
        'widgetAlias' => $widgetAlias,
        'widget' => $widget,
    ]); ?>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH /home/vagrant/code/public/app/admin/widgets/dashboardcontainer/widget_list.blade.php ENDPATH**/ ?>
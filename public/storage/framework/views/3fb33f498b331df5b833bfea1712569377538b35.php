<div class="row-fluid">
    <?php echo $this->widgets['toolbar']->render(); ?>


    <?php echo $this->makePartial('updates/search', ['itemType' => 'extension']); ?>


    <?php echo $this->widgets['list_filter']->render(); ?>


    <?php echo $this->widgets['list']->render(); ?>

</div>
<?php /**PATH /home/vagrant/code/public/app/system/views/extensions/index.blade.php ENDPATH**/ ?>
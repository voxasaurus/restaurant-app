<div class="row-fluid">
    <?php echo form_open([
        'id'     => 'edit-form',
        'role'   => 'form',
        'method' => 'PATCH',
    ]); ?>


    <?php echo $this->renderForm(); ?>


    <?php echo form_close(); ?>

</div>
<?php /**PATH /home/vagrant/code/public/app/admin/views/menus/edit.blade.php ENDPATH**/ ?>
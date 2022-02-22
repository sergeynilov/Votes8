<?php if ( Session::has('action_status') and Session::has('action_text')) { ?>
    <div class="alert alert-<?php echo session('action_status') ?> alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> <?php echo session('action_text') ?>
    </div>
<?php } ?>
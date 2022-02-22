<?php $error_was_shown = false; ?>
<?php if ( Session::has('action_status') and Session::has('action_text')) { ?>
<div class="alert alert-<?php echo session('action_status') ?> alert-dismissable card-subtitle" id="div_action_text_alert">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> <?php echo session('action_text') ?>
</div>
<?php $error_was_shown = true; ?>
<?php } ?>


@if (isset($errors) and $errors->any() and !$error_was_shown)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
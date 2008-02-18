<h1><?php e(__('Forgotten password')); ?></h1>

<?php if (isset($msg)): ?>
    <p><strong><?php e($msg); ?></strong></p>
<?php endif; ?>

<form method="POST" action="<?php e(url('./')); ?>" id="forgotten_email_form">
    <p><?php e(__('Silly you. Forgot your password. Lucky we are here to help!')); ?></p>
    <fieldset>
        <label id="email_label"><?php e(__('Email')); ?></label>
        <input type="text" name="email" id="email"  />
        <input type="submit" name="submit" value="<?php e(__('Help')); ?>" id="submit" />
    </fieldset>
</form>

<div class="users form">
<?php echo $this->Form->create('User'); ?>
    <fieldset>
        <legend>
            <?php echo __('Please enter your username and password'); ?>
        </legend>
        <?php echo $this->Form->input('username');
        echo $this->Form->input('password');
		//TODO Forgot
    ?>
    </fieldset>
<?php echo $this->Form->end(__('Login')); ?>
</div>
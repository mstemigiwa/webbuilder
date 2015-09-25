
<div class="users form">
<?php echo $this->Form->create('User'); ?>
<fieldset>
	<legend><?php echo __('Create Account') ?></legend>
	<?php
		echo $this->Form->input('firstname',array('label'=>'First Name'));
		echo $this->Form->input('lastname',array('label'=>'Last Name'));
		echo $this->Form->input('company',array('label'=>'Company'));
		echo $this->Form->input('address',array('type'=>'textarea','label'=>'Address'));
		echo $this->Form->input('phone',array('label'=>'Phone Number'));
		echo $this->Form->input('username',array('label'=>'Email'));
		echo $this->Form->input('password');
		echo $this->Form->input('password2',array('label'=>'Re-Enter Password','type'=>'password'));
	?>
</fieldset>
<?php echo $this->Form->end(array('label'=>'Register')); ?>
</div>

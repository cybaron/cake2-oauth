<?php
echo $this->Form->create('User', array('action' => 'signup'));
echo $this->Form->input('username');
echo $this->Form->input('email');
echo $this->Form->input('password', array('type' => 'password', 'label' => 'Password'));
echo $this->Form->input('password_confirm', array('type' => 'password', 'label' => 'Password Confirm'));
echo $this->Form->end('create');
?>


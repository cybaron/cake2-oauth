<p>
login success! <br>
Hi! <?php echo $user['username']; ?>
</p>
<p><?php echo $this->Html->link('logout', '/users/logout'); ?></p>
<p><?php echo $this->Html->link('user delete', '/users/delete', array(), "削除しても良いですか？"); ?></p>

<div>Logged in as <?php echo $this->view->username ?></div>
<div class="menu_container">
  <a href="<?php $this->base_uri('auth/logout') ?>">Logout</a> | 
  <a href="<?php $this->base_uri('') ?>">Home</a> | 
  <a href="<?php $this->base_uri('add/vocab') ?>">Add Vocab</a> | 
  <a href="<?php $this->base_uri('add/kanji') ?>">Add Kanji</a> | 
  <a href="<?php $this->base_uri('find') ?>">Find Data</a> | 
  <a href="<?php $this->base_uri('update/counters') ?>">Update</a> | 
</div>

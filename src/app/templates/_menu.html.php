<div>Logged in as <?php echo $this->view->username ?></div>
<div class="menu_container">
  <a href="<?php $this->base_uri('auth/logout') ?>">Logout</a> | 
  <a href="<?php $this->base_uri('') ?>">Home</a> | 
  <a href="<?php $this->base_uri('new/vocab') ?>">New Vocab</a> | 
  <a href="<?php $this->base_uri('new/kanji') ?>">New Kanji</a> | 
  <a href="<?php $this->base_uri('find') ?>">Find Data</a> | 
  <a href="<?php $this->base_uri('update/counters') ?>">Update</a> | 
</div>

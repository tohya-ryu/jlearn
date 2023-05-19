<div>Logged in as <?php echo $this->view->username ?></div>
<div id="menu-container">
  <div>
    <a href="<?php $this->base_uri('auth/logout') ?>">Logout</a>
  </div>
  <div>
    <a href="<?php $this->base_uri('') ?>">Home</a>
  </div>
  <div>
    <a href="<?php $this->base_uri('new/vocab') ?>">New Vocab</a>
  </div>
  <div>
    <a href="<?php $this->base_uri('new/kanji') ?>">New Kanji</a>
  </div>
  <div>
    <a href="<?php $this->base_uri('find') ?>">Find Data</a>
  </div>
  <div>
    <a href="<?php $this->base_uri('update/counters') ?>">Update</a>
  </div>
</div>

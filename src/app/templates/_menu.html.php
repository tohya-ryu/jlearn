<!-- <div>Logged in as <?php echo $this->view->username ?></div> -->
<nav id="menu-container">

  <a class="menu-link button" href="<?php $this->base_uri('auth/logout') ?>">
    Logout
  </a>

<?php if ($this->get('page') == 'practice'): ?>
  <span class="button-disabled menu-link-disabled">Practice</span>
<?php else : ?>
  <a class="menu-link button" href="<?php $this->base_uri('') ?>">
    Practice
  </a>
<?php endif ?>

<?php if ($this->get('page') == 'new_vocab'): ?>
  <span class="button-disabled menu-link-disabled">New Word</span>
<?php else : ?>
  <a class="menu-link button" href="<?php $this->base_uri('new/vocab') ?>">
    New Word
  </a>
<?php endif ?>

<?php if ($this->get('page') == 'new_kanji'): ?>
  <span class="button-disabled menu-link-disabled">New Kanji</span>
<?php else : ?>
  <a class="menu-link button" href="<?php $this->base_uri('new/kanji') ?>">
    New Kanji
  </a>
<?php endif ?>

<?php if ($this->get('page') == 'search'): ?>
  <span class="button-disabled menu-link-disabled">Search</span>
<?php else : ?>
  <a class="menu-link button" href="<?php $this->base_uri('find') ?>">
    Search
  </a>
<?php endif ?>

<?php if ($this->get('page') == 'update'): ?>
  <span class="button-disabled menu-link-disabled">Update</span>
<?php else : ?>
  <a class="menu-link button" 
    href="<?php $this->base_uri('update/counters') ?>">Update</a>
<?php endif ?>

</nav>

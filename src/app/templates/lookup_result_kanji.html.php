<?php if ($this->view->lookup_result): ?>

<?php $i = 1; ?>

<?php foreach ($this->view->lookup_result as $row): ?>

  <?php if ($i == 1): ?>
    <div class="alert">Maybe your kanji already exists?</div>
  <?php endif ?>
  <div><span class="kanji_title"><?php echo $row->kanji ?></span></div>
  <div class="line"></div>
  <div><?php echo $row->onyomi ?></div>
  <div><?php echo $row->kunyomi ?></div>
  <div class="line"></div>
  <div class="lookup-limiter"><?php echo $row->meanings ?></div>
  <?php $i++ ?>

<?php endforeach ?>


<?php else: ?>
  Kanji not found in database
<?php endif ?>

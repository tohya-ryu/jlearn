<?php if ($this->view->lookup_result): ?>

<?php $i = 1; ?>

<?php foreach ($this->view->lookup_result as $row): ?>

  <?php if ($i == 1): ?>
    <div class="alert">Maybe your vocab already exists?</div>
  <?php endif ?>
  <div><span class="kanji_title"><?php echo $row->kanji_name ?></span></div>
  <div class="line"></div>
  <div><?php echo $row->hiragana_name ?></div>
  <div class="line"></div>
  <div class="lookup-limiter"><?php echo $row->meanings ?></div>
  <?php $i++ ?>

<?php endforeach ?>


<?php else: ?>
  Vocab not found in database
<?php endif ?>

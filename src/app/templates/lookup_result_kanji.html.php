<?php if ($this->view->lookup_result): ?>

<?php $i = 1; ?>

<?php foreach ($this->view->lookup_result as $row): ?>

<div class="input-container">
  <?php if ($i == 1): ?>
    <div class="alert bm">Maybe your kanji already exists?</div>
  <?php endif ?>
  <div class="bm"><span class="kanji_title"><?php echo $row->kanji ?></span></div>
  <div class="line"></div>
  <div class ="bm"><?php echo $row->onyomi ?></div>
  <div class="bm"><?php echo $row->kunyomi ?></div>
  <div class="line"></div>
  <div class="lookup-limiter bm"><?php echo $row->meanings ?></div>
  <?php $i++ ?>
</div>

<?php endforeach ?>


<?php else: ?>
  Kanji not found in database
<?php endif ?>

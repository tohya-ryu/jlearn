<?php $this->view->render('_menu.html.php', array('page'=>'')); ?>

<h2 class="title">
<?php echo $this->view->formdata->get_current_word_counter() ?> / 
<?php echo $this->view->formdata->get_query_counter() ?>
</h2>

<div class="input-container">
  <span class="kanji_title"><?php echo $this->view->kanji->kanji ?></span>
</div>

<div class="input-container">
  <div class="bm">
    <a onclick="document.getElementById('meanings').style.display = 'block'">
      定義表示 </a>
  </div>
  <div id="meanings" class="content bm hidden">
    <?php echo $this->view->kanji->meanings; ?>
  </div>
</div>

<div class="input-container">
  <div class="bm">
    <a onclick="document.getElementById('onyomi').style.display = 'block'">
      おんよみ表示 </a>
  </div>
  <div id="onyomi" class="hiragana_title bm hidden">
    <?php echo $this->view->kanji->onyomi; ?>
  </div>
</div>

<div class="input-container">
  <div class="bm">
    <a onclick="document.getElementById('kunyomi').style.display = 'block'">
      くんよみ表示 </a>
  </div>
  <div id="kunyomi" class="hiragana_title bm hidden">
    <?php echo $this->view->kanji->kunyomi; ?>
  </div>
</div>

<div class="bm">
  <form action="<?php $this->base_uri('practice/kanji') ?>" method="post" 
      accept-charset="utf-8">

    <input type="hidden" name="csrf-token" 
      value="<?php echo $this->view->csrf_token ?>" />

    <select name="update_type">
      <option value="1" selected>Ignore</option>
      <option value="2">Good</option>
      <option value="3">Bad</option>
    </select>

    <input type="hidden" name="id" 
      value="<?php echo $this->view->formdata->get_id() ?>" />
    <input type="hidden" name="counter" 
      value="<?php echo $this->view->kanji->counter ?>" />
    <input type="hidden" name="success" 
      value="<?php echo $this->view->kanji->success_counter ?>" />
    <input type="hidden" name="miss" 
      value="<?php echo $this->view->kanji->miss_counter ?>" />

    <input type="hidden" name="search_by_kanji" 
      value="<?php $this->print('search_kanji') ?>" />
    <input type="hidden" name="custom" 
      value="<?php $this->print('tags') ?>" />
    <input type="hidden" name="custom_timespace" 
      value="<?php $this->print('custom_timespace') ?>" />
    <input type="hidden" name="timespace_type" 
      value="<?php $this->print('timespace_type') ?>" />
    <input type="hidden" name="timespace_start" 
      value="<?php $this->print('timespace_start') ?>" />
    <input type="hidden" name="timespace_end" 
      value="<?php $this->print('timespace_end') ?>" />
    <input type="hidden" name="order_rule" 
      value="<?php $this->print('order_rule') ?>" />
    <input type="hidden" name="ignore_latest" 
      value="<?php $this->print('ignore_latest') ?>" />
    <input type="hidden" name="counter_limit" 
      value="<?php $this->print('counter_limit') ?>" />
    <input type="hidden" name="success_limit" 
      value="<?php $this->print('success_limit') ?>" />
    <input type="hidden" name="jlpt" 
      value="<?php $this->print('jlpt') ?>" />
    <input type="hidden" name="min_words" 
      value="<?php $this->print('min_words') ?>" />
    <input type="hidden" name="max_words" 
      value="<?php $this->print('max_words') ?>" />
    <input type="hidden" name="query_counter" 
      value="<?php $this->print('query_counter') ?>" />
    <input type="hidden" name="current_word_counter" 
      value="<?php $this->print('current_word_counter') ?>" />

    <p><input class="button" type="submit" name="form-submit" value="次へ" /></p>

  </form>
</div>

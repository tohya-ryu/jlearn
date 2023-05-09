<?php $this->view->render('_menu.html.php'); ?>

<div>
<?php echo $this->view->formdata->get_current_word_counter() ?> / 
<?php echo $this->view->formdata->get_query_counter() ?>

</div>

<div>
  <span class="kanji_title"><?php echo $this->view->vocab->kanji_name ?></span>
  <span class="wordtype">(<?php $this->print('word_type_text') ?>)</span>
</div>

<div class="line"></div>

<div>
  <a onclick="document.getElementById('hiragana').style.display = 'block'">
    ひらがな表示
  </a>
</div>

<div class="line"></div>

<div id="hiragana" class="hiragana_title">
  <?php echo $this->view->vocab->hiragana_name; $this->print('word_trans') ?>
</div>

<div class="line"></div>

<div>
  <a onclick="document.getElementById('content').style.display = 'block'">
    回答表示 </a>
</div>

<div class="line"></div>

<div id="content" class="content">
  <?php echo $this->view->vocab->meanings; ?>
</div>

<div class="line"></div>
<div>
  <form action="<?php $this->base_uri('practice/vocab') ?>" method="post" 
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
      value="<?php echo $this->view->vocab->counter ?>" />
    <input type="hidden" name="success" 
      value="<?php echo $this->view->vocab->success_counter ?>" />
    <input type="hidden" name="miss" 
      value="<?php echo $this->view->vocab->miss_counter ?>" />

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
    <input type="hidden" name="type" 
      value="<?php $this->print('type') ?>" />
    <input type="hidden" name="transitivity" 
      value="<?php $this->print('transitivity') ?>" />
    <input type="hidden" name="query_counter" 
      value="<?php $this->print('query_counter') ?>" />
    <input type="hidden" name="current_word_counter" 
      value="<?php $this->print('current_word_counter') ?>" />

    <div class="line"></div>

    <p><input type="submit" name="form-submit" value="次へ" /></p>

  </form>
</div>
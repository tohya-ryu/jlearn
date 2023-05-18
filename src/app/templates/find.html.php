<?php $this->view->render('_menu.html.php'); ?>

<div class="left_container">
  <form id="find" class="framework-form" method="post">
    <div class="framework-validation-notice"></div>

    <input type="hidden" id="csrf-token" name="csrf-token"
      value="<?php echo $this->view->csrf_token ?>"/>

    <div>
      <label for="find-type">Find in</label>
    </div>
    <div> 
      <select id="find-type" name="find-type">
        <option value="1" selected>Words by Kanji</option>
        <option value="2">Words by Hiragana</option>
        <option value="3">Words by Meanings</option>
        <option value="4">Kanji by Kanji</option>
        <option value="5">Kanji by Onyomi</option>
        <option value="6">Kanji by Kunyomi</option>
        <option value="7">Kanji by Meanings</option>
      </select>
    </div>

    <div>
      <label for="find-string">Search string</label>
    </div>
    <div>
      <input type="text" name="find-string" id="find-string" size="12" 
        maxlen="120" />
    </div>

    <div>
      <input type="submit" value="Submit" name="submit" id="submit" />
    </div>

  </form>

</div>

<div class="right_container">
  <div id="keyword_found_container"></div>
</div>
<div class="clearfix"></div>

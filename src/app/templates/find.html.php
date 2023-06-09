<?php $request = FrameworkRequest::get() ?>
<?php $this->view->render('_menu.html.php', array('page'=>'search')); ?>

<h2 class="title">Search Data</h2>

<div class="left_container-collapse">
  <form id="find" class="framework-form" method="post">
    <div class="framework-validation-notice"></div>

    <input type="hidden" id="csrf-token" name="csrf-token"
      value="<?php echo $this->view->csrf_token ?>"/>

    <div class="input-container-collapse">
      <label for="find-type">Find in</label>
      <select id="find-type" name="find-type">
        <option value="1" selected>Words by Kanji</option>
        <option value="2">Words by Hiragana</option>
        <option value="3">Words by Meanings</option>
        <option value="4">Words by Tags</option>
        <option value="5">Kanji by Kanji</option>
        <option value="6">Kanji by Onyomi</option>
        <option value="7">Kanji by Kunyomi</option>
        <option value="8">Kanji by Meanings</option>
        <option value="9">Kanji by Tags</option>
      </select>
    </div>

    <div class="input-container-collapse">
      <label for="find-string">Search string</label>
      <input type="text" name="find-string" id="find-string" size="12" 
        maxlen="120" />
    </div>

    <div>
      <input class="button" type="submit" value="Submit" name="submit" id="submit" />
    </div>

  </form>

</div>

<div class="right_container">
  <div id="keyword_found_container">
    <?php if ($this->view->find_result): ?>
      <?php if ($request->param->post('find-type')->value < 5): ?>
        <?php foreach ($this->view->find_result as $vocab): ?>
        <div class="input-container">
          <div>
            <a href="<?php $this->base_uri('edit/vocab/'.$vocab->id); ?>" 
              class="kanji_title"><?php echo $vocab->kanji_name ?>
            </a>
          </div>
          <div class="line"></div>
          <div>
            <?php echo $vocab->hiragana_name ?>
          </div>
          <div class="line"></div>
          <div class="lookup-limiter">
            <?php echo $vocab->meanings ?>
          </div>
          <div class="line"></div>
          <div class="lookup-limiter">
            <?php echo $vocab->tags ?>
          </div>
        </div>
        <?php endforeach ?>
      <?php else : ?>
        <?php foreach ($this->view->find_result as $kanji): ?>
        <div class="input-container">
          <div>
            <a href="<?php $this->base_uri('edit/kanji/'.$kanji->id); ?>" 
              class="kanji_title"><?php echo $kanji->kanji ?>
            </a>
          </div>
          <div class="line"></div>
          <div>
            <?php echo $kanji->onyomi ?>
          </div>
          <div>
            <?php echo $kanji->kunyomi ?>
          </div>
          <div class="line"></div>
          <div class="lookup-limiter">
            <?php echo $kanji->meanings ?>
          </div>
          <div class="line"></div>
          <div class="lookup-limiter">
            <?php echo $kanji->tags ?>
          </div>
        </div>
        <?php endforeach ?>
      <?php endif ?>
    <?php endif ?>
  </div>
</div>
<div class="clearfix"></div>

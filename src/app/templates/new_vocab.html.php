<?php $this->view->render('_menu.html.php'); ?>

<div class="left_container">
  <form id="vocab-new" class="framework-form" data-method="post">
    <div class="framework-validation-notice"></div>

    <input type="hidden" id="csrf-token" name="csrf-token"
      value="<?php echo $this->view->csrf_token ?>"/>

    <div>
      <label for="kanji">Kanji</label>
      <input type="text" name="kanji" id="kanji" 
        placeholder="Japanese word in kanji if applicable" 
        maxlength="120" size="50" />
      <div id="validation-errors-kanji"></div>
    </div>

    <div>
      <label for="hiragana">Hiragana</label>
      <input type="text" name="hiragana" id="hiragana" 
        placeholder="Japanese word in kana" maxlength="120" size="50" />
      <div id="validation-errors-hiragana"></div>
    </div>
  
    <div>
      <label for="meanings">Translation</label>
      <textarea name="meanings" id="meanings" rows="15" cols="30"></textarea>
      <div id="validation-errors-meanings"></div>
    </div>

    <div>
      <label>Word Types</label>

      <select name="wtype1" id="wtype1">
      <?php $this->view->render('_wtype_options.html.php',
        array('default' =>'-')); ?>
      </select>

      <select name="wtype2" id="wtype2">
      <?php $this->view->render('_wtype_options.html.php',
        array('default' =>'-')); ?>
      </select>

      <select name="wtype3" id="wtype3">
      <?php $this->view->render('_wtype_options.html.php',
        array('default' =>'-')); ?>
      </select>

      <select name="wtype4" id="wtype4">
      <?php $this->view->render('_wtype_options.html.php',
        array('default' =>'-')); ?>
      </select>

      <select name="wtype5" id="wtype5">
      <?php $this->view->render('_wtype_options.html.php',
        array('default' =>'-')); ?>
      </select>

      <select name="wtype6" id="wtype6">
      <?php $this->view->render('_wtype_options.html.php',
        array('default' =>'-')); ?>
      </select>

      <select name="wtype7" id="wtype7">
      <?php $this->view->render('_wtype_options.html.php',
        array('default' =>'-')); ?>
      </select>
      <div id="validation-errors-wtype1"></div>
      <div id="validation-errors-wtype2"></div>
      <div id="validation-errors-wtype3"></div>
      <div id="validation-errors-wtype4"></div>
      <div id="validation-errors-wtype5"></div>
      <div id="validation-errors-wtype6"></div>
      <div id="validation-errors-wtype7"></div>
    </div>

    <div>
      <label for="transitivity">Verb Transitivity</label>
      <select name="transitivity">
        <option value="0" selected>-</option>
        <option value="1">Transitive</option>
        <option value="2">Intransitive</option>
        <option value="3">Both</option>
      </select>
      <div id="validation-errors-transitivity"></div>
    </div>

    <div>
      <label for="jlpt">JLPT Level</label>
      <select name="jlpt">
        <option value="0" selected>-</option>
        <option value="1">N1</option>
        <option value="2">N2</option>
        <option value="3">N3</option>
        <option value="4">N4</option>
        <option value="5">N5</option>
      </select>
      <div id="validation-errors-jlpt"></div>
    </div>

    <div>
      <label for="tags">Tags</label>
      <input type="text" name="tags" id="tags" 
        placeholder="Tags separated by pipes (|)" 
        maxlength="255" size="100" />
      <div id="validation-errors-tags"></div>
    </div>

    <div>
      <input type="checkbox" name="allow-duplicate" 
        id="allow-duplicate" />
      <label for="allow-duplicate">Allow duplicate</label>
    </div>

    <div>
      <button type="button" data-uri="<?php $this->base_uri("new/vocab"); ?>" 
        class="framework-form-submit">
        Submit
      </button>
    </div>

  </form>

</div>

<div class="right_container">
  <div id="keyword_found_container"></div>
</div>
<div class="clearfix"></div>

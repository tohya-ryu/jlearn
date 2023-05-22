<div class="input-container-collapse">
  <label for="kanji">Kanji</label>
  <input type="text" name="kanji" id="kanji" 
    placeholder="Japanese word in kanji if applicable" 
    maxlength="120" size="50" data-type="vocab" 
    value="<?php $this->print('kanji'); ?>" />
  <div id="validation-errors-kanji"></div>
</div>

<div class="input-container-collapse">
  <label for="hiragana">Hiragana</label>
  <input type="text" name="hiragana" id="hiragana" 
    placeholder="Japanese word in kana" maxlength="120" size="50"
    value="<?php $this->print('hiragana'); ?>" />
  <div id="validation-errors-hiragana"></div>
</div>

<div class="input-container-collapse">
  <label for="meanings">Translation</label>
  <textarea name="meanings" id="meanings" rows="15" cols="30"><?php $this->print('meanings'); ?></textarea>
  <div id="validation-errors-meanings"></div>
</div>

<div class="input-container-collapse">
  <label>Word Types</label>

  <select name="wtype1" id="wtype1" 
    data-default="<?php $this->print('wtype1') ?>">
  <?php $this->view->render('_wtype_options.html.php',
    array('default' =>'-',
      'default_value' => $this->get('wtype1'))); ?>
  </select>

  <select name="wtype2" id="wtype2"  
    data-default="<?php $this->print('wtype2') ?>">
  <?php $this->view->render('_wtype_options.html.php',
    array('default' =>'-',
      'default_value' => $this->get('wtype2'))); ?>
  </select>

  <select name="wtype3" id="wtype3"  
    data-default="<?php $this->print('wtype3') ?>">
  <?php $this->view->render('_wtype_options.html.php',
    array('default' =>'-',
      'default_value' => $this->get('wtype3'))); ?>
  </select>

  <select name="wtype4" id="wtype4" 
    data-default="<?php $this->print('wtype4') ?>">
  <?php $this->view->render('_wtype_options.html.php',
    array('default' =>'-',
      'default_value' => $this->get('wtype4'))); ?>
  </select>

  <div>
    <select name="wtype5" id="wtype5" 
      data-default="<?php $this->print('wtype5') ?>">
    <?php $this->view->render('_wtype_options.html.php',
      array('default' =>'-',
        'default_value' => $this->get('wtype5'))); ?>
    </select>

    <select name="wtype6" id="wtype6" 
      data-default="<?php $this->print('wtype6') ?>">
    <?php $this->view->render('_wtype_options.html.php',
      array('default' =>'-',
        'default_value' => $this->get('wtype6'))); ?>
    </select>

    <select name="wtype7" id="wtype7" 
      data-default="<?php $this->print('wtype7') ?>">
    <?php $this->view->render('_wtype_options.html.php',
      array('default' =>'-',
        'default_value' => $this->get('wtype7'))); ?>
    </select>
  </div>
  <div id="validation-errors-wtype1"></div>
  <div id="validation-errors-wtype2"></div>
  <div id="validation-errors-wtype3"></div>
  <div id="validation-errors-wtype4"></div>
  <div id="validation-errors-wtype5"></div>
  <div id="validation-errors-wtype6"></div>
  <div id="validation-errors-wtype7"></div>
</div>

<div class="input-container-collapse">
  <label for="transitivity">Verb Transitivity</label>
  <select name="transitivity" id="transitivity" 
    data-default="<?php $this->print('transitivity') ?>">
    <option value="0"
      <?php if ($this->get('transitivity') == 0) { echo 'selected'; } ?>
      >-</option>
    <option value="1"
      <?php if ($this->get('transitivity') == 1) { echo 'selected'; } ?>
      >Transitive</option>
    <option value="2"
      <?php if ($this->get('transitivity') == 2) { echo 'selected'; } ?>
      >Intransitive</option>
    <option value="3"
      <?php if ($this->get('transitivity') == 3) { echo 'selected'; } ?>
      >Both</option>
  </select>
  <div id="validation-errors-transitivity"></div>
</div>

<div class="input-container-collapse">
  <label for="jlpt">JLPT Level</label>
  <select name="jlpt" id="jlpt" 
    data-default="<?php $this->print('jlpt') ?>">
    <option value="0"
      <?php if ($this->get('jlpt') == 0) { echo 'selected'; } ?>
      >-</option>
    <option value="1"
      <?php if ($this->get('jlpt') == 1) { echo 'selected'; } ?>
      >N1</option>
    <option value="2"
      <?php if ($this->get('jlpt') == 2) { echo 'selected'; } ?>
      >N2</option>
    <option value="3"
      <?php if ($this->get('jlpt') == 3) { echo 'selected'; } ?>
      >N3</option>
    <option value="4"
      <?php if ($this->get('jlpt') == 4) { echo 'selected'; } ?>
      >N4</option>
    <option value="5"
      <?php if ($this->get('jlpt') == 5) { echo 'selected'; } ?>
      >N5</option>
  </select>
  <div id="validation-errors-jlpt"></div>
</div>

<div class="input-container-collapse">
  <label for="tags">Tags</label>
  <input type="text" name="tags" id="tags" 
    placeholder="Tags separated by pipes (|)" 
    maxlength="255" size="100" 
    value="<?php $this->print('tags'); ?>" />
  <div id="validation-errors-tags"></div>
</div>

<div>
  <label for="kanji">Kanji Character</label>
  <input type="text" name="kanji" id="kanji" maxlength="3" size="3" 
    value="<?php $this->print('kanji'); ?>" />
  <div id="validation-errors-kanji"></div>
</div>

<div>
  <label for="onyomi">Onyomi</label>
  <input type="text" name="onyomi" id="onyomi" 
    placeholder="Chinese readings" maxlength="255" size="50"
    value="<?php $this->print('onyomi'); ?>" />
  <div id="validation-errors-onyomi"></div>
</div>

<div>
  <label for="kunyomi">Kunyomi</label>
  <input type="text" name="kunyomi" id="kunyomi" 
    placeholder="Japanese readings" maxlength="255" size="50"
    value="<?php $this->print('kunyomi'); ?>" />
  <div id="validation-errors-kunyomi"></div>
</div>

<div>
  <label for="meanings">Translation</label>
  <textarea name="meanings" id="meanings" rows="15" cols="30"><?php $this->print('meanings'); ?></textarea>
  <div id="validation-errors-meanings"></div>
</div>

<div>
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

<div>
  <label for="tags">Tags</label>
  <input type="text" name="tags" id="tags" 
    placeholder="Tags separated by pipes (|)" 
    maxlength="255" size="100" 
    value="<?php $this->print('tags'); ?>" />
  <div id="validation-errors-tags"></div>
</div>

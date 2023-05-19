<?php $this->view->render('_menu.html.php'); ?>

<?php

$time = (new DateTime())->getTimestamp();
$ignore_recent   = $time - (86400 / 4);
$ignore_today    = $time - 86400;
$ignore_week     = $time - 604800;
$ignore_twoweeks = $time - 1209600;
$ignore_month    = $time - 2419200;

?>

<!-- Vocab Form -->

<div class="words_filter">
  <span class="title">Vocab Filter</span>
  <form action="<?php $this->base_uri('practice/vocab') ?>" method="post" 
    accept-charset="utf-8">

    <input type="hidden" name="csrf-token" 
      value="<?php echo $this->view->csrf_token ?>" />
    <input type="hidden" name="practice-start" value="true" />
    
    <p>Look for a specific kanji</p>
    <input name="search_by_kanji" value="" maxlength="10" size="10" />
    <div class="line"></div>

    <p>Order by</p>
    <select name="order_rule">
      <option value="0" selected>Random</option>
      <option value="1">Success Rate (asc)</option>
      <option value="2">Learn Date (asc)</option>
      <option value="3">Entry Date (asc)</option>
      <option value="4">Learn Date (desc)</option>
    </select>
    <div class="line"></div>

    <p>Limit by Learn Date (recently learned words are ignored)</p>
    <select name="ignore_latest">
      <option value="<?php echo $time ?>">Ignore none</option>
      <option value="<?php echo $ignore_recent ?>">Ignore last 6 hours</option>
      <option value="<?php echo $ignore_today ?>" selected>
        Ignore last 24 hours
      </option>
      <option value="<?php echo $ignore_week ?>">Ignore last 7 days</option>
      <option value="<?php echo $ignore_twoweeks ?>">Ignore last 2 weeks
      </option>
      <option value="<?php echo $ignore_month ?>">Ignore last 4 weeks</option>
    </select>
    <p>Or use custom time interval (yyyy-mm-dd hh:mm)</p>

    <p>Use custom time interval: 
    <input type="checkbox" name="custom_timespace" value="accept" />
    <select name="timespace_type">
      <option value="1" selected>Limit By Entry Date</option>
      <option value="2">Limit By Learn Date</option>
    </select></p>
    <p>From
    <?php $timespace = date("Y-m-d 00:00", $time-86400); ?>
    <input type="text" name="timespace_start" 
      value="<?php echo $timespace ?>" maxlength="16" size="16" />
    to 
    <?php $timespace = date("Y-m-d 00:00", $time); ?>
    <input type="text" name="timespace_end" 
      value="<?php echo $timespace ?>" maxlength="16" size="16" /></p>
    <div class="line"></div>
    
    <p>Learned no more than <i>x</i> times (set 0 to fetch unlearned words)</p>
    <input type="text" name="counter_limit" value="9999" maxlength="11" 
      size="10" />
    <div class="line"></div>

    <p>Maximum Success Rate</p>
    <input type="text" name="success_limit" value="100" maxlength="3" 
      size="3"/>
    <div class="line"></div>

    <p>JLPT Filter</p>
    <select name="jlpt">
      <option value="0" selected>Filter Off</option>
      <option value="1">N1</option>
      <option value="2">N2</option>
      <option value="3">N3</option>
      <option value="4">N4</option>
      <option value="5">N5</option>
    </select>
    <div class="line"></div>

    <p>Type Filter</p>
    <select name="type">
      <?php $this->view->render('_wtype_options.html.php',
        array('default' =>'Filter Off', 'default_value'=>0)); ?>
    </select>
    <select name="transitivity">
      <option value="0" selected>-</option>
      <option value="1">Transitive</option>
      <option value="2">Intransitive</option>
      <option value="3">Both</option>
    </select>
    <div class="line"></div>

    <p>Tags (custom filters)</p>
    <input type="text" name="custom" value="" maxlength="120" size="80" />
    <div class="line"></div>

    <p><input type="submit" name="form-submit" value="練習開始" /></p>
    
  </form>
</div>

<!-- Kanji Form -->

<div class="words_filter">
  <span class="title">Kanji Filter</span>
  <form action="<?php $this->base_uri('practice/kanji') ?>" method="post" 
    accept-charset="utf-8">

    <input type="hidden" name="csrf-token" 
      value="<?php echo $this->view->csrf_token ?>" />
    <input type="hidden" name="practice-start" value="true" />
    
    <p>Look for a specific kanji</p>
    <input name="search_by_kanji" value="" maxlength="10" size="10" />
    <div class="line"></div>

    <p>Order by</p>
    <select name="order_rule">
      <option value="0" selected>Random</option>
      <option value="1">Success Rate (asc)</option>
      <option value="2">Learn Date (asc)</option>
      <option value="3">Entry Date (asc)</option>
      <option value="4">Learn Date (desc)</option>
    </select>
    <div class="line"></div>

    <p>Limit by Learn Date (recently learned words are ignored)</p>
    <select name="ignore_latest">
      <option value="<?php echo $time ?>">Ignore none</option>
      <option value="<?php echo $ignore_recent ?>">Ignore last 6 hours</option>
      <option value="<?php echo $ignore_today ?>" selected>
        Ignore last 24 hours
      </option>
      <option value="<?php echo $ignore_week ?>">Ignore last 7 days</option>
      <option value="<?php echo $ignore_twoweeks ?>">Ignore last 2 weeks
      </option>
      <option value="<?php echo $ignore_month ?>">Ignore last 4 weeks</option>
    </select>
    <p>Or use custom time interval (yyyy-mm-dd hh:mm)</p>

    <p>Use custom time interval: 
    <input type="checkbox" name="custom_timespace" value="accept" />
    <select name="timespace_type">
      <option value="1" selected>Limit By Entry Date</option>
      <option value="2">Limit By Learn Date</option>
    </select></p>
    <p>From
    <?php $timespace = date("Y-m-d 00:00", $time-86400); ?>
    <input type="text" name="timespace_start" 
      value="<?php echo $timespace ?>" maxlength="16" size="16" />
    to 
    <?php $timespace = date("Y-m-d 00:00", $time); ?>
    <input type="text" name="timespace_end" 
      value="<?php echo $timespace ?>" maxlength="16" size="16" /></p>
    <div class="line"></div>
    
    <p>Learned no more than <i>x</i> times (set 0 to fetch unlearned words)</p>
    <input type="text" name="counter_limit" value="9999" maxlength="11" 
      size="10" />
    <div class="line"></div>

    <p>Number of associated words (min - max)</p>
    <input type="text" name="min_words" value="0" maxlength="11" size="10" />
    <input type="text" name="max_words" value="20000" maxlength="11" 
      size="10" />
    <div class="line"></div>

    <p>Maximum Success Rate</p>
    <input type="text" name="success_limit" value="100" maxlength="3" 
      size="3"/>
    <div class="line"></div>

    <p>JLPT Filter</p>
    <select name="jlpt">
      <option value="0" selected>Filter Off</option>
      <option value="1">N1</option>
      <option value="2">N2</option>
      <option value="3">N3</option>
      <option value="4">N4</option>
      <option value="5">N5</option>
    </select>
    <div class="line"></div>

    <p>Tags (custom filters)</p>
    <input type="text" name="custom" value="" maxlength="120" size="80" />
    <div class="line"></div>

    <p><input type="submit" name="form-submit" value="練習開始" /></p>
    
  </form>
</div>

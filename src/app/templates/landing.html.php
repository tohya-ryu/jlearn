<?php $this->view->render('_menu.html.php'); ?>

<?php

$time = time();
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
      <option value="2" selected>Limit By Learn Date</option>
    </select></p>
    <p>From
    <?php $timespace = date("Y-m-d 00:00", time()-86400); ?>
    <input type="text" name="timespace_start" 
      value="<?php echo $timespace ?>" maxlength="16" size="16" />
    to 
    <?php $timespace = date("Y-m-d 00:00", time()); ?>
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
      <option value="1" selected>N1</option>
      <option value="2" selected>N2</option>
      <option value="3" selected>N3</option>
      <option value="4" selected>N4</option>
      <option value="5" selected>N5</option>
    </select>
    <div class="line"></div>

    <p>Type Filter</p>
    <select name="type">
      <option value="0" selected>Filter Off</option>
      <option value="1" selected>Noun</option>
      <option value="2" selected>Verb (suru)</option>
      <option value="3" selected>Verb (ichidan)</option>
      <option value="4" selected>Verb (godan)</option>
      <option value="5" selected>Adjective (i)</option>
      <option value="6" selected>Adjective (na)</option>
      <option value="11" selected>Adjective (no)</option>
      <option value="14" selected>Adjective (taru)</option>
      <option value="7" selected>Adverb</option>
      <option value="15" selected>Adverb (to)</option>
      <option value="8" selected>Expression</option>
      <option value="9" selected>Conjunction</option>
      <option value="10" selected>Prenominal</option>
      <option value="12" selected>Prefix</option>
      <option value="13" selected>Suffix</option>
      <option value="16" selected>Place</option>
      <option value="17" selected>Name</option>
    </select>
    <select name="transitivity">
      <option value="0" selected>-</option>
      <option value="1" selected>Transitive</option>
      <option value="2" selected>Intransitive</option>
      <option value="3" selected>Both</option>
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
      <option value="2" selected>Limit By Learn Date</option>
    </select></p>
    <p>From
    <?php $timespace = date("Y-m-d 00:00", time()-86400); ?>
    <input type="text" name="timespace_start" 
      value="<?php echo $timespace ?>" maxlength="16" size="16" />
    to 
    <?php $timespace = date("Y-m-d 00:00", time()); ?>
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
      <option value="1" selected>N1</option>
      <option value="2" selected>N2</option>
      <option value="3" selected>N3</option>
      <option value="4" selected>N4</option>
      <option value="5" selected>N5</option>
    </select>
    <div class="line"></line>

    <p>Tags (custom filters)</p>
    <input type="text" name="custom" value="" maxlength="120" size="80" />
    <div class="line"></div>

    <p><input type="submit" name="form-submit" value="練習開始" /></p>
    
  </form>
</div>

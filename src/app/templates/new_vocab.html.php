<?php $this->view->render('_menu.html.php', array('page'=>'new_vocab')); ?>

<h2 class="title">New Word</h2>

<div class="left_container">
  <form id="vocab-new" class="framework-form" data-method="post">
    <div class="framework-validation-notice"></div>

    <input type="hidden" id="csrf-token" name="csrf-token"
      value="<?php echo $this->view->csrf_token ?>"/>

    <?php $this->view->render('_vocab_form_body.html.php',
      $this->view->formdata); ?>

    <div class="input-container">
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

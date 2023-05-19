<?php $this->view->render('_menu.html.php', array('page'=>'')); ?>

<div class="left_container">
  <form id="vocab-edit" class="framework-form" data-method="post">
    <div class="framework-validation-notice"></div>

    <input type="hidden" id="csrf-token" name="csrf-token"
      value="<?php echo $this->view->csrf_token ?>"/>

    <input type="hidden" id="id" name="id"
      value="<?php echo $this->view->vocab->id ?>" /> 

    <?php $this->view->render('_vocab_form_body.html.php',
      $this->view->formdata); ?>

    <div>
      <input type="checkbox" name="allow-duplicate" 
        id="allow-duplicate" />
      <label for="allow-duplicate">Allow duplicate</label>
    </div>

    <div>
      <button type="button" data-uri="<?php $this->base_uri("edit/vocab"); ?>" 
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

<div>
    <b>SIGN UP</b>
</div>
<div>
    <form id="auth-signup" class="framework-form" data-method="post">
        <div class="framework-validation-notice"></div>
        <div>
            <label for="auth-name">Username</label>
            <input type="text" id="auth-name" name="auth-name"/>
            <div id="validation-errors-auth-name"></div>
        </div>
        <div>
            <label for="auth-email">E-Mail</label>
            <input type="email" id="auth-email" name="auth-email"/>
            <div id="validation-errors-auth-email"></div>
        </div>
        <div>
            <label for="auth-password">Password</label>
            <input type="password" id="auth-password" 
              name="auth-password"/>
            <div id="validation-errors-auth-password"></div>
        </div>
        <div>
            <label for="auth-password-check">Password Check</label>
            <input type="password" id="auth-password-check" 
              name="auth-password-check"/>
            <div id="validation-errors-auth-password-check"></div>
        </div>
          <input type="hidden" id="csrf-token" name="csrf-token"
            value="<?php echo $this->view->csrf_token ?>"/>
        <div>
          <button type="button" 
            data-uri="<?php $this->base_uri('auth/signup'); ?>" 
            class="framework-form-submit">
            <?php echo Framework::locale()->button_signup(); ?>
          </button>
        </div>  
    </form>    
</div>

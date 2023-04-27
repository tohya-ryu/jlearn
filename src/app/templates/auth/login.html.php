<div>
  <b>LOGIN</b>
</div>
<div>
  <form>
    <div>
      <label for="auth-email">E-Mail</label>
      <input type="text" id="auth-email"/>
    </div>
    <div>
      <label for="auth-password">Password</label>
      <input type="password" id="auth-password"/>
    </div>
    <input type="hidden" id="csrf-token" name="csrf-token"
      value="<?php echo $this->view->csrf_token ?>"/>
    <div>
      <button type="button" onclick="send_form()">Submit</button>
    </div>  
  </form>    
  <div>
    <a href="<?php $this->base_uri('auth/signup'); ?>">Sign Up</a> <br>
    <a href="
      <?php $this->base_uri('auth/pwreset/apply'); ?>">I forgot my password
    </a>
  </div>
</div>

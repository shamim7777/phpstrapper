<style type="text/css">
    .hide{display: none}
</style>

<div class="account-container">
    <div class="form-content clearfix">
        <form id="register" name="register" action="register/create" >
            <h1><i class="iconbig-note-write"></i><?php echo  _l("Sign up"); ?></h1>	        
            <div class="login-fields">         

                <div class="field">
                    <label for="username"><?php echo  _l("Username"); ?>:</label>
                    <div class="control-group">
                        <div class="controls">
                            <input type="text" required data-validation-ajax-ajax="/register/usernamecheck" data-validation-ajax-message="Username already taken" class="login username-field" placeholder="<?php echo  _l("Username"); ?>" value="" name="username" id="username">
                        </div>
                    </div>  
                </div>

                <div class="field">
                    <label for="email"><?php echo  _l("Email"); ?>:</label>
                    <div class="control-group">
                        <div class="controls">
                            <input type="email" data-validation-ajax-ajax="/register/emailcheck" data-validation-ajax-message="Email address already taken" name="email" value="" placeholder="<?php echo  _l("Email"); ?>" class="login email-field" required />
                        </div>
                    </div>  
                </div> 
                <div class="field">
                    <label for="password"><?php echo  _l("Password"); ?>:</label>
                    <div class="control-group">
                        <div class="controls">
                            <input type="password"  name="password" value="" placeholder="<?php echo  _l("Password"); ?>" class="login password-field" required />
                        </div>
                    </div>  
                </div> 
            </div> 			
            <div class="login-actions">
                <input type="hidden" name="token" value="<?php echo $_SESSION['coderangers']['token']; ?>"/>
                <input type="submit" name="submit" value="<?php echo _l("Sign up"); ?>" class="btn-signin btn btn-primary dosignup" />              
                <a href="#" class="btn-signin btn"><?php echo _l("Cancel"); ?></a>
            </div> <!-- .actions -->
            <div class="login-social marg10-btm">
                <p><?php echo _l("Sign up using social network"); ?>:</p>
                <a href="#" class="btn"><img src="<?php echo BASE_URL ?>assets/img/twitter-18.png" /><?php echo  _l("Signup with twitter"); ?></a>
                <a href="#" class="btn"><img src="<?php echo BASE_URL ?>assets/img/facebook-18.png" /><?php echo  _l("Signup with facebook"); ?></a>			
            </div>
        </form>
    </div> <!-- /form-content -->
</div> <!-- /account-container -->

<!--
 <div id="content">
          <div class="container">
                <div id="middle">
                    <div class="row" style="float:left;">
                          <div class="span12">
                            <div class="well well-form txt-lefty">
                      <h3>Sign Up</h3>
                     
                                  <form id="register" action="register/create" >
                                <div class="control-group">
                                  <div class="controls">
                                                <input type="text" name="name" placeholder="Name" class="span3" required>
                                          </div>
                                    </div>				    
                                <div class="control-group">
                                  <div class="controls">
                                                <input data-validation-ajax-ajax="/register/usernamecheck" data-validation-ajax-message="Username already taken" type="text" name="username" placeholder="Username" class="span3" required>
                                          </div>
                                    </div>	
                                <div class="control-group">
                                  <div class="controls">
                                                <input data-validation-ajax-ajax="/register/emailcheck" data-validation-ajax-message="Email address already taken" type="email" name="email" placeholder="Email" class="span3" required>
                                          </div>
                                    </div>
                                <div class="control-group">
                                  <div class="controls">
                                                <input type="password" name="password" placeholder="Password" class="span3" required>
                                          </div>
                                    </div>
                                <div class="control-group">
                                  <div class="controls">
                                                <input type="password" name="passwordagain" placeholder="Confirm Password" class="span3" required data-validation-matches-match="password" data-validation-matches-message="Must match password entered above">
                                          </div>
                                    </div>
                        <div class="form-actions">
                                      <label class="checkbox">
                            <input type="checkbox" name="terms" required  data-validation-required-message="You must agree to the terms and conditions">
                            I agree to the terms and conditions
                          </label>
                                      <button class="btn btn-primary dosignup" type="submit">Sign Up</button>
                        </div> 
                      </form>
                                         <div class="form-extra">
                                            <button class="btn btn-info"><i class="glyphicons-twitter"></i> Login with Twitter</button>
                                            <button class="btn btn-danger"><i class="glyphicons-google_plus"></i> Login with Google</button>
                                          </div> 
                    </div>
                          </div>  
                        </div>  	
                        <div class="row" style="float:left;">
                          <div class="span12">
                            <div class="well well-form txt-lefty">
                      <h3>Sign In</h3>
                                  <form>
                                <div class="control-group">
                                  <div class="controls">
                                        <div class="input-prepend">
                                          <span class="add-on"><i class="icon-user"></i></span>
                                                  <input type="text" name="username" placeholder="Username" class="span3" required>
                                        </div>
                                          </div>
                                    </div>
                                    <div class="control-group"> 
                                  <div class="controls">
                                        <div class="input-prepend">
                                          <span class="add-on"><i class="icon-lock"></i></span>
                                                  <input type="password" name="password" placeholder="Password" class="span3" required>
                                        </div>
                                          </div>			
                                </div>
                        <div class="form-actions">
                                      <label class="checkbox">
                            <input type="checkbox" name="remember">
                            Remember Me
                          </label>
                                      <button class="btn btn-primary dosignin" type="button">Sign In</button>
                        </div> 
                      </form>
                                                  
                    </div>
                          </div>  
                        </div>  	

                </div>

          </div>
        </div>
-->
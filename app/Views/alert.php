<div id="content">
	  <div class="container">
	    <div class="row">
		  <div class="span12">
		    <div class="well well-form txt-lefty">
             <div class="alert alert-<?php echo $info['class'] ?>">
				<h3><?php echo $info['message'] ?></h3>
		 
		   	</div>		  
            </div>
		  </div>  
		</div>  	
	  </div>
	</div>

<div id="forgotpassword" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Retrieve your password</h3>
    </div>
    <form name="fpform" id="fpform"> 
        <div class="modal-body login-fields">

            <div class="field">
                <label for="email">Email:</label>
                <div class="control-group">
                    <div class="controls">
                        <input type="email" data-validation-ajax-ajax="/register/emailexistance" data-validation-ajax-message="Email address already taken" name="email" value="" placeholder="Email" class="login email-field" required />
                        <input type="hidden" name="token" value="<?php echo $_SESSION['coderangers']['token']; ?>"/>
                    </div>
                </div>  
            </div> 
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
            <button class="btn btn-primary">Retrieve</button>
    </form>
</div>
</div>

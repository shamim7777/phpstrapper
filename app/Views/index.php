<style type="text/css">
    .nav-headers {height: 215px;}
    input.span3{width: 198px;}
    span.login-checkbox {margin-top: 5px;}
    #signinh1{margin: 0 0 10px 0; font-size: 20px;}
    .account-settings {float: left;height: 75px;padding: 5px;width: 70px;margin: 0px;}
    .account-detail {float: left; width: 145px;}
    .account-detail > p {font-size: 11px;margin: 0 0 5px 5px;}
    .account-detail > hr {margin: 10px 0;}
</style>
<div id="fb-root"></div>
<div class="container">
    <div class="row">
        <div class="span3">
            <div class="nav-headers">

                <div class="well2 well-form txt-lefty">
                    <?php if (!isset($_SESSION['coderangers']['username'])) : ?>
                        <h1 id="signinh1"><i class="iconbig-lock"></i><?php echo _l("Sign in"); ?></h1> 
                        <form action="/account/login" type="POST" id="login" name="login">
                            <div class="control-group">
                                <div class="controls">
                                    <div class="input-prepend">
                                        <span class="add-on"><i class="icon-user"></i></span>
                                        <input type="text" name="username" placeholder="<?php echo _l("Username"); ?>" class="span3" required>
                                    </div>
                                </div>
                            </div>
                            <div class="control-group"> 
                                <div class="controls">
                                    <div class="input-prepend">
                                        <span class="add-on"><i class="icon-lock"></i></span>
                                        <input type="password" name="password" placeholder="<?php echo _l("Password"); ?>" class="span3" required>
                                        <input type="hidden" name="token" value="<?php echo $_SESSION['coderangers']['token']; ?>"/>
                                    </div>
                                </div>            
                            </div>
                            <div class="form-actions2">
                                <span class="login-checkbox">

                                    <input type="checkbox" tabindex="4" value="First Choice" class="field login-checkbox" name="remember" id="Field">

                                    <label for="Field" class="choice"> <?php echo _l("Keep me signed in"); ?></label>

                                </span>
                                <div style="clear:both;"> </div>
                                <div>
                                    <button class="btn btn-primary dosignin" type="Submit"><?php echo _l("Sign in"); ?></button>
                                    <div class="marg10-btm" style="float:right;">

                                        <a class="btn" href="register/facebooklogin/"><img src="<?php echo BASE_URL ?>assets/img/facebook-18.png"> <?php echo _l("Signin facebook"); ?></a>         

                                    </div>
                                </div>
                                <!-- Button to trigger modal -->

                            </div> 
                            <a style="margin:10px 0" href="#forgotpassword" id="forgotpasswordlink" role="button" class="btn" data-toggle="modal"><?php echo _l("Forgot password?"); ?></a>
                        </form>
                    <?php else : ?>
                        <div class="account-settings">

                            <img src="<?php echo $account_info['thumb'] ?>" />

                        </div>
                        <div class="account-detail">
                            <p><strong><?php echo _l("Member Since"); ?> :</strong></p>
                            <p><span class="i18n-date"><?php echo date('F j, Y', strtotime($account_info['timestamp'])); ?></span></p>
                            <?php if (!empty($account_info['location'])) : ?>
                                <p><strong>Location : </strong></p>
                                <p><?php echo $account_info['location']; ?></p>
                            <?php endif; ?>
                            <?php if (!empty($account_info['website'])) : ?>
                                <p><strong>Website : </strong></p>
                                <p><a target='black' href="<?php echo $account_info['website']; ?>"><?php echo $account_info['website']; ?></a></p>
                            <?php endif; ?>
                            <hr />
                            <p><a href="/profile/<?php echo $account_info['username']; ?>"><?php echo _l("View Public Profile"); ?></a></p>
                        </div>
                    <?php endif; ?>
                </div>


            </div>
            <ul class="nav nav-tabs nav-stacked">
                <li class="active"><a rel="newest" href="javascript:;" class="sortvideo"><i class="icon-picture"></i> <?php echo _l("Newest"); ?></a></li>
                <li><a rel="popular" href="javascript:;" class="sortvideo"><i class="icon-ok"></i> <?php echo _l("Popular"); ?></a></li>
                <li><a rel="topchannels" href="javascript:;" class="sortvideo"><i class="icon-certificate"></i> <?php echo _l("Top Channels"); ?></a></li>
                <li><a rel="mostviewed" href="javascript:;" class="sortvideo"><i class="icon-bookmark"></i> <?php echo _l("Most Viewed"); ?></a></li>
                <li><a rel="categories" href="javascript:;" class="loadcategories"><i class="icon-th-list"></i> <?php echo _l("Categories"); ?></a></li>
                <li><a rel="mostdownload" href="javascript:;" class="sortvideo"><i class="icon-download"></i> <?php echo _l("Most Download"); ?></a></li>
                <li><a rel="recommended" href="javascript:;" class="sortvideo"><i class="icon-heart"></i> <?php echo _l("Recommended"); ?> </a></li>
            </ul>

            <ul class="nav nav-tabs nav-stacked">
                <li class="active"><a rel="newest" href="javascript:;" class="sortvideo"><i class="icon-picture"></i> <?php echo _l("Newest"); ?></a></li>
                <li><a rel="popular" href="javascript:;" class="sortvideo"><i class="icon-ok"></i> <?php echo _l("Popular"); ?></a></li>
                <li><a rel="topchannels" href="javascript:;" class="sortvideo"><i class="icon-certificate"></i> <?php echo _l("Top Channels"); ?></a></li>
                <li><a rel="mostviewed" href="javascript:;" class="sortvideo"><i class="icon-bookmark"></i> <?php echo _l("Most Viewed"); ?></a></li>
                <li><a rel="categories" href="javascript:;" class="loadcategories"><i class="icon-th-list"></i> <?php echo _l("Categories"); ?></a></li>
                <li><a rel="mostdownload" href="javascript:;" class="sortvideo"><i class="icon-download"></i> <?php echo _l("Most Download"); ?></a></li>
                <li><a rel="recommended" href="javascript:;" class="sortvideo"><i class="icon-heart"></i><?php echo _l("Recommended"); ?> </a></li>
            </ul> 
        </div>

        <div class="span9">
            <h2 id="videolist-title"><?php echo _l("Newest Videos"); ?></h2>
        </div>

        <div id='xxx'></div>

        <div id="videolist"></div>


        <div id="pagination" style="float:right"> </div>

    </div>
</div>

<div id="forgotpassword" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel"><?php echo _l("Retrieve your password"); ?></h3>
    </div>
    <form name="fpform" id="fpform"> 
        <div class="modal-body login-fields">

            <div class="field">
                <label for="email"><?php echo _l("Email"); ?>:</label>
                <div class="control-group">
                    <div class="controls">
                        <input type="email" data-validation-ajax-ajax="/register/emailexistance" data-validation-ajax-message="Email address already taken" name="email" value="" placeholder="<?php echo _l("Email"); ?>" class="login email-field" required />
                        <input type="hidden" name="token" value="<?php echo $_SESSION['coderangers']['token']; ?>"/>
                    </div>
                </div>  
            </div> 
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo _l("Close"); ?></button>
            <button class="btn btn-primary"><?php echo _l("Retrieve"); ?></button>
        </div>
    </form>

</div>

<script type="text/x-tmpl" id="tmpl-videolist">
    {% for (var i=0; i<o.videolist.length; i++) { %}
    <div class="span3 galery">
    <div class="menu-galery">
    <ul id="vid_{%=o.videolist[i].id%}">
    <li><a href="/video/play/{%=o.videolist[i].id%}" rel="tooltip" title="<?php echo _l("Detail"); ?>"><i class="iconbig-search"></i></a></li>
    <li><a href="/video/play/{%=o.videolist[i].id%}#commentanchor" rel="tooltip" title="<?php echo _l("Add Comment"); ?>"><i class="iconbig-speak"></i></a></li>
    <li><a href="#" rel="tooltip" title="<?php echo _l("Download"); ?>"><i class="iconbig-download"></i></a></li>
    <li><a class="like-video {%=(o.videolist[i].user_likes) ? ' opacity-active' : '' %}" href="#" rel="tooltip" title="<?php echo _l("Like"); ?>"><i class="iconbig-black-star"></i></a></li>
    </ul>
    </div>
    <div class="title-galery">{%=o.videolist[i].title%}</div>
    <div class="image-galery">
    <a class="group" rel="group1" href="/video/play/{%=o.videolist[i].id%}">
    <img src="{%=o.videolist[i].poster_http_url%}" />
    <span class="overlay"></span>            
    </a>
    </div>
    <div class="count-galery">
    <ul>
    <li><i class="icon-comment"></i> <span class="i18n-n">{%=o.videolist[i].total_comments%}</span></li>
    <li><i class="icon-download-alt"></i> <span class="i18n-n">{%=o.videolist[i].downloaded%}</span></li>
    <li><i class="icon-star"></i> <span class="i18n-n like-counter">{%=o.videolist[i].total_likes%}</span></li>
    <li><i class="icon-eye-open"></i> <span class="i18n-n">{%=o.videolist[i].viewed%}</span></li>
    </ul>
    </div>
    <div class="tags-galery">
    <p><i class="icon-tags"></i> <?php echo _l("Tags"); ?> : 
    {% for (var j=0; j<o.videolist[i].tags.length; j++) { %}
    <a class="sortvideo" rel="{%=o.videolist[i].tags[j]%}" href="javascript:;">{%=o.videolist[i].tags[j]%}</a> 
    {% } %}
    </p>
    </div>
    </div>
    {% } %}
</script>

<script type="text/x-tmpl" id="tmpl-videocatlist">

    {% for (var i=0; i<o.catlist.length; i++) { %}

    <div class="span3 galery">

    <div class="title-galery">{%=o.catlist[i].name%}</div>

    <div class="image-galery">

    <a class="group sortvideo" rel="{%=o.catlist[i].name%}" href="#">

    <img src="{%=o.catlist[i].poster%}" />

    <span class="overlay"></span>            

    </a>

    </div>

    <div class="count-galery">
    <ul>
    <li><i class="icon-film"></i> {%=o.catlist[i].total_videos%}</li>
    </ul>
    </div>

    </div>

    {% } %}

</script>



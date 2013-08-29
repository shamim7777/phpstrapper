<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php echo $title; ?> | Home</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="<?php echo META_DESCRIPTION ?>">

        <meta name="keywords" content="<?php echo SITE_KEYWORDS ?>">
        <meta name="author" content="Shamim Ahmed">
        <link href="<?php echo BASE_URL ?>assets/css/bootstrap.css" rel="stylesheet">
        <link href="<?php echo BASE_URL ?>assets/css/bootstrap-responsive.min.css" rel="stylesheet">
        <link href="<?php echo BASE_URL ?>assets/css/style.css" rel="stylesheet">  
        <link href="<?php echo BASE_URL ?>assets/css/icon-style.css" rel="stylesheet">
        <link href="<?php echo BASE_URL ?>assets/fileuploader/fineuploader.css" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo BASE_URL ?>assets/js/chosen/chosen.css" />
        
        <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>-->
        <script>window.jQuery || document.write('<script src="<?php echo BASE_URL ?>assets/js/jquery-1.8.3.min.js"><\/script>')</script>
            
        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <!-- Le fav and touch icons -->
        <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
        <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    
        <style type="text/css">
            .qq-upload-button input[type='file'] {
                width: 100% !important;
                cursor: pointer;
            }
            #thumb-select img {
                margin-right: 5px;
                display: inline-block;
                cursor: pointer;
            }

        #thumb-select img.selected {
            background-color: #52A652;
        }
    </style>
    <script type="text/javascript">
        var baseUrl = '<?php echo BASE_URL; ?>';
        var avatarUrl = '<?php echo AVATAR_URL ?>';
        var avatarThumbUrl = '<?php echo AVATAR_THUMB_URL ?>';
        var videoUrl = '<?php echo VIDEO_URL ?>';
        var videoPosterUrl = '<?php echo POSTER_URL ?>';
    </script>
  </head>
 
  <body>
        <div class="navbar navbar-fixed-top navbar-inverse">
            <div class="navbar-inner">
                <div class="container-fluid">
                    <a class="brand" href="/home"><img src="<?php echo BASE_URL ?>assets/img/logo.png"/></a>
                    <div class="nav-collapse">
                        <ul class="nav">
                            <li class="divider-vertical"></li>
                            <li><a href="/home"><?php echo  _l("Home"); ?></a></li>
                            <li class="divider-vertical"></li>
                            <form class="navbar-search pull-left" action="">
                                <input type="text" class="search-query span2" placeholder="<?php echo  _l("Search"); ?>" required>
                            </form>
                            <li class="divider-vertical"></li>
                        </ul>
                        <ul class="nav ace-nav pull-right">
                            <?php if (!isset($_SESSION['coderangers']['username'])) { ?>
                                <li class="divider-vertical"></li>
                                <li><a href="/home"><i class="icon-lock icon-white"></i><?php echo  _l("Sign in"); ?></a></li>
                                <li class="divider-vertical"></li>
                                <li><a href="/register"><i class="icon-edit icon-white"></i><?php echo  _l("Sign up"); ?></a></li>
                                <li class="divider-vertical"></li>
                                <li class="facebook-link grey">
                                    <a href="register/facebook" class="btn">
                                        <img style="margin-right: 2px;" src="<?php echo BASE_URL; ?>assets/img/facebook-18.png" /><?php echo  _l("Signup with facebook"); ?>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if (isset($_SESSION['coderangers']['username'])) { ?>
                                <li class='green'><a href="/upload"><i class="icon-camera icon-white" style="margin: 2px 2px 0px 0px;"></i><?php echo  _l("Upload Video"); ?></a></li>
                                <li class="divider-vertical"></li>
                                <li class="light-blue user-profile">
                                    <a class="user-menu dropdown-toggle" href="#" data-toggle="dropdown">
                                        <img alt="<?php echo $_SESSION['coderangers']['username']; ?>'s <?php echo  _l("Photo"); ?>" src="<?php echo  $_SESSION['thumb']  ?>" class="nav-user-photo">
                                        <span id="user_info">
                                            <small><?php echo  _l("Welcome"); ?>,</small>
                                            <?php echo $_SESSION['coderangers']['username']; ?>
                                        </span>

                                        <i class="icon-chevron-down icon-white"></i>
                                    </a>

                                    <ul id="user_menu" class="pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-closer">
                                        <li>
                                            <a href="/account">
                                                <i class="icon-cog"></i>
                                                <?php echo  _l("Settings"); ?>
                                            </a>
                                        </li>

                                        <li>
                                            <a href="/profile/<?php echo $_SESSION['coderangers']['username']; ?>">
                                                <i class="icon-user"></i>
                                                <?php echo  _l("Profile"); ?>
                                            </a>
                                        </li>
                                        <li>
                                            <?php
                                            
                                            if($_SESSION['lang']=='bd')
                                            $lang ='en'; 

                                            if($_SESSION['lang']=='en')
                                            $lang ='bd';

                                            $url = $_SERVER["REQUEST_URI"];
                                            $url = explode('?',$url);
                                            $url = $url[0];    
                                            ?>
                                            <a href="<?php echo $url ?>?lang=<?php echo  $lang?>">
                                                <i class="icon-align-justify"></i>
                                                <?php echo  _l("Interface Language"); ?>
                                            </a>
                                        </li>

                                        <li class="divider"></li>

                                        <li>
                                            <a href="/account/logout">
                                                <i class="icon-off"></i>
                                                <?php echo  _l("Logout"); ?>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            <?php } ?>
                            <li class="divider-vertical"></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

<?php

/*

1. Save this file.
2. Rename to index.php
3. Upload to your web server.
4. You are finished!

*/


$enableLogin = 1; //set to 0 to secure your site.

/*
Ultrose is copyright and wholly owned by Dan Nagle (http://dannagle.com/).
It is Dual-Licensed under GPLv3 or Commercial.

Commercial licenses range from removing GPL to full OEM (removing copyright).
See Ultrose.com for details: http://ultrose.com/


If you did not purchase a commercial license, then this software is GPLv3.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
    

Since this is one file, for brevity, the GPL text is not included.
You may look here for the text: http://www.gnu.org/licenses/gpl-3.0.txt


*/

global $title, $slogan, $desciption, $yourname, $email, $content, $baseurl, $postsFrontPage, $filetypes;

// Basic configuration 
$title = "Your Site";
$slogan = "Your Slogan";
$desciption = "Your Description"; //required by RSS
$yourname = "You";
$email = "you@example.com"; //site contact email

$password = "password";
$copyright = $yourname; 

//Choose your theme from the list (further) below.
$theme = "pepper-grinder";
//uncomment to daily rotate the available themes.
//$theme = "random";

//I recommend you put a real base url instead of using my calculation
//Use a trailing slash if needed.
//Example: "http://example.com/ultrose/"; 
//Example: "http://ultrose.com/"; 
$baseurl = getBaseUrl();


/*
 Copy-paste-modify content template to add your own content.
 Your content starts and stops with the key ULTROSECONTENT
Use <!--break--> or <!--more--> to break content for "Read more".
*/

$content[] = array(
"title" => "About Site",
"date" => "Oct 13, 2011",
"category" => "ultrose",
"permalink" => "about_me",
"content" => <<<ULTROSECONTENT

This is a post without a "Read More". 

ULTROSECONTENT
);


$content[] = array(
"title" => "Read More Example",
"date" => "Oct 13, 2011",
"category" => "ultrose",
"permalink" => "readmore",
"content" => <<<ULTROSECONTENT

This is a post that has a read more.
<!--break-->
<br>
Here is the read more.

ULTROSECONTENT
);


$enableSiteContact = 1; //set to 0 to disable.
$enableFileBrowser = 1; //set to 0 to disable.
$enableThemeRotate = 1; //set to 0 to disable.
$enableFacebookLike = 1; //set to 0 to disable.
$enableTwitter = 1; //set to 0 to disable. 
$twitterAccount = "NagleCode"; //put your twitter account here.

$postsFrontPage = 5; //number of posts to allow on front page.

//public file browser does not allow uploads
 
//browseable file types (when not logged in)
$filetypes = "7z tar gz txt zip exe dmg pdf doc docx
            xls xlsx mp3 mpg ogg flv msi wav png gif
            jpg jpeg avi mov mp4";

/*
 
Copy-paste then modify nav template to add your own links.
 
$navbarlinks[] = <<<ULTROSECONTENT
Your link goes here.
ULTROSECONTENT;

Your content starts and stops with the key ULTROSECONTENT
 
*/


$navbarlinks[] = <<<ULTROSECONTENT
<a href="http://dannagle.com/">Dan Nagle</a>
ULTROSECONTENT;

$navbarlinks[] = <<<ULTROSECONTENT
<a href="http://ultrose.com/">Ultrose</a>
ULTROSECONTENT;



//place your analytics code here.

$GoogleAnalyticsCode = <<<ULTROSECONTENT



ULTROSECONTENT;


//22 available Google-hosted themes. Comment them out to keep them out of rotation
$themes[] = "ui-lightness";
$themes[] = "sunny";
$themes[] = "ui-darkness";
$themes[] = "redmond";
$themes[] = "overcast";
$themes[] = "le-frog";
$themes[] = "flick";
$themes[] = "pepper-grinder";
$themes[] = "eggplant";
$themes[] = "cupertino";
$themes[] = "dark-hive";
$themes[] = "south-street";
$themes[] = "blitzer";
$themes[] = "humanity";
$themes[] = "hot-sneaks";
$themes[] = "excite-bike";
$themes[] = "vader";
$themes[] = "dot-luv";
$themes[] = "mint-choc";
$themes[] = "black-tie";
$themes[] = "trontastic";
$themes[] = "swanky-purse";


/*
  DO NOT CHANGE ANYTHING BELOW THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING!
*/



$loggedIn = false;
$error = false;
$success = false; 

if ($enableLogin == false)
{
    setcookie("password", "", time()-100);  // force expire
}


if(stripos($theme, "random") !== false)
{
        //crude method to "seed" the random.
        //don't want all our blogs to show the same "random" theme each day, do we?
    $titlehashhex = md5($title);
        //chop it down a bit to ease memory/logic.
    $titlehashhex = substr($titlehashhex, 0, floor(strlen($titlehashhex) / 3));
    $themeseed = (hexdec($titlehashhex));


    $choosetheme = abs(($themeseed + date('j')) % (count($themes)));

    $theme = $themes[$choosetheme];
}


if(isset($_REQUEST['theme']) && $enableThemeRotate)
{
    if(isset($_GET['theme']))
    {
        $testtheme = strtolower(trim($_GET['theme']));
        
    } else {
        $testtheme = strtolower(trim($_REQUEST['theme']));
    }
    $testchoosetheme = array_search($testtheme, $themes);
    
    if($testchoosetheme !== false)
    {
        $theme = $testtheme;
        $choosetheme = $testchoosetheme;
        setcookie("theme", $testtheme);  // session cookie for setting theme

    } else {
        $error = "Theme not available";
        setcookie("theme", $testtheme, time() - 500);
    }
    
     //choosetheme
}


$pagerequest = false;
if(isset($_REQUEST['page']))
{
    $pagerequest = $_REQUEST['page'];
    
}



if($pagerequest == "logout")
{
    setcookie("password", "", time()-100);  // force expire
    $success = "You are now logged out."; 
          
}


if(isset($_POST['password']) && $enableLogin)
{
    if($password == $_POST['password'])
    {
        setcookie("password", md5($_POST['password'] +"salt"), time()+432000);  // expire in 5 days
        $loggedIn = true;
        $success = "Login Successful";

    } else {
        $error = "Bad password.";
    }
    
} else {
    
    if(isset($_COOKIE['password']))
    {
        
        if($_COOKIE['password'] == md5($password +"salt") && $enableLogin)
        {
            $loggedIn = true;
        }
        
    }
    
}

if(isset($_POST['internalemail']) && $loggedIn)
{
    mailer(htmlspecialchars($_POST['toemail']),
        htmlspecialchars($_POST['name']),
        htmlspecialchars($_POST['fromemail']),
        htmlspecialchars($_POST['subject']),
        htmlspecialchars($_POST['message']));
    $success = "Message sent";
    
} elseif (isset($_POST['contact']) && $enableSiteContact)
{
    mailer(                      $email,
        htmlspecialchars($_POST['name']),
        htmlspecialchars($_POST['email']),
        htmlspecialchars($title)." Site Contact: ".htmlspecialchars($_POST['subject']),
        htmlspecialchars($_POST['message']));
    $success = "Message sent";
}

if($pagerequest == "rss")
{
    outputRSS();
    exit;
}


if($pagerequest && $loggedIn)
{
    if($pagerequest == "phpinfo")
    {
        echo phpinfo();
        exit;
    }
}

if (!empty($_FILES) && $loggedIn)
{
            
    if($loggedIn && isset($_REQUEST['directory']))
    {
        $directory = "./".trimDotsSlashes($_REQUEST['directory']);
        if (!move_uploaded_file($_FILES["uploadfile"]["tmp_name"], 
$directory."/".$_FILES["uploadfile"]["name"]))
        {
            $error="Error uploading file.";
            
        } else {
            $success = "Sucessfully uploaded ".trimDotsSlashes($directory."/".$_FILES["uploadfile"]["name"]); 
        }
        

    }

}



if(isset($_REQUEST['command']) && $loggedIn)
{
    //prevent going back directories.
    $from = "./".trimDotsSlashes($_REQUEST['from']);
    $to = "./".trimDotsSlashes($_REQUEST['to']);
    if($_REQUEST['command'] == "move")
    {
        
        if(rename($from, $to) === false)
        {
            echo "Error";
        }
        exit;
    }
    
    if($_REQUEST['command'] == "delete")
    {
        if(unlink($from) === false)
        {
            echo "Error";
        }
        exit;
    }
    
    if($_REQUEST['command'] == "copy")
    {
        if (copy($from, $to) === false)
        {
            echo "Error";
        }
        exit;
    }
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo htmlspecialchars($title." | ".$slogan); ?></title>
<link rel="alternate" type="application/rss+xml" title="<?php echo $title; ?> Primary Feed" href="<?php echo 
$baseurl."?page=rss"; ?>" />
<META NAME="DESCRIPTION" CONTENT="<?php echo $desciption; ?>">
<META NAME="Generator" CONTENT="Ultrose 1.0">
    
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js" type="text/javascript"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/jquery-ui.min.js" 
type="text/javascript"></script>

<script type="text/javascript">
function padValue(val, padVal)
{
    var returnVal = val + padVal;
    if(returnVal < 0)
    {
        returnVal = 0;
    }
    if (returnVal > 255)
    {
        returnVal = 255;
    }
    
    return returnVal;
}

function massageRGB (rgbstring, padVal)
{
    var rgbvalue = rgbstring.split(',');
    var temp = rgbvalue[0].split('(');
    var rvalue = padValue(parseInt(temp[1], 10), padVal);
    var gvalue = padValue(parseInt(rgbvalue[1], 10) , padVal);
    var bvalue = padValue(parseInt(rgbvalue[2], 10) , padVal);
    return "rgb(" + rvalue + ", "+ gvalue + ", "+ bvalue + ")";
}


$(document).ready(function(){

    $('#uploadbutton').click(function() {
      $('#uploadform').submit();
    });


    $(".movebutton").click(function () { 

        var move = prompt("Move/Rename to where?",$(this).attr("title"));
        if (move!=null && move!="")
        {
          $.post("<?php echo $baseurl;?>", { command: "move", from: $(this).attr("title"), to: move},
            function(data) {
                if(data.length > 2)
                {
                    alert(data);
                } else {
                    location.reload(true);
                    
                }
          });
          
        }

    });
    $(".deletebutton").click(function () { 

	  var answer = confirm('Delete ' + $(this).attr("title") + '?'); 
	  if(answer)
	  {
          $.post("<?php echo $baseurl;?>", { command: "delete", from: $(this).attr("title")},
            function(data) {
                if(data.length > 2)
                {
                    alert(data);
                } else {
                    location.reload(true);
                    
                }
          });
	  
	  }
    });
    $(".copybutton").click(function () { 

        var move = prompt("Copy to where?",$(this).attr("title"));
        if (move!=null && move!="")
        {
          $.post("<?php echo $baseurl;?>", { command: "copy", from: $(this).attr("title"), to: move},
            function(data) {
                if(data.length > 2)
                {
                    alert(data);
                } else {
                    location.reload(true);
                    
                }
          });
          
        }

    });

    $("#loginlink").click(function () { 
    $("#loginblock").dialog({
        height: 300,
        width: 400,
        modal:true,
        buttons: {
			"Login": function() { 
				$("#loginform").submit(); 
			}, 
			"Cancel": function() { 
				$(this).dialog("close"); 
			} 
		}
        });
        return false;
    });
    
    
});

</script>

<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/themes/<?php echo 
$theme;?>/jquery-ui.css" type="text/css" />



<style type='text/css'>

body, html {
    margin:0;
    padding:0;
    color:#000;
    background:#000059;
}

#errorblock {
    width:400px;
    margin:0 auto;

}
#doublewrap {
    width:1000px;
    margin:0 auto;

}


#wrap
{
    width:950px;
    margin:0 auto;
}


#wrap, #doublewrap
{
    background-color:  
   <?php
    //My own personal tweaking... 
    switch ($theme) {
        case "ui-lightness": echo "#EEEEEE;";break;
        case "sunny": echo "#FEEEBD;";break;
        case "ui-darkness": echo "#252525;";break;
        case "redmond": echo "#edf3f3;";break;
        case "overcast": echo "#C9C9C9;";break;
        case "le-frog": echo "#285C00;";break;
        case "flick": echo "#eeeeee;";break;
        case "pepper-grinder": echo "#ECEADF;";break;
        case "eggplant": echo "#3D3644;";break;
        case "cupertino": echo "#F2F5F7;";break;
        case "dark-hive": echo "#3c3c3c;";break;
        case "south-street": echo "#eeebd5;";break;
        case "blitzer": echo "#eeebd5;";break;
        case "humanity": echo "#F4F0EC;";break;
        case "hot-sneaks": echo "#e6e9ec;";break;
        case "excite-bike": echo "#EEEEEE;";break;
        case "vader": echo "#121212;";break;
        case "dot-luv": echo "#2c2c2c;";break;
        case "mint-choc": echo "#3d2f25;";break;
        case "black-tie": echo "#e6e6e6;";break;
        case "trontastic": echo "#1e3c00;";break;
        case "swanky-purse": echo "#443113;";break;
        default: echo "#FFFFFF;";break;
    }

    echo "\ncolor:";
    switch ($theme) {
        case "ui-lightness": echo "#333333;";break;
        case "sunny": echo "#383838;";break;
        case "ui-darkness": echo "#FFFFFF;";break;
        case "redmond": echo "#222222;";break;
        case "overcast": echo "#333333;";break;
        case "le-frog": echo "#FFFFFF;";break;
        case "flick": echo "#444444;";break;
        case "pepper-grinder": echo "#1F1F1F;";break;
        case "eggplant": echo "#FFFFFF;";break;
        case "cupertino": echo "#362B36;";break;
        case "dark-hive": echo "#FFFFFF;";break;
        case "south-street": echo "#312E25;";break;
        case "blitzer": echo "#333333;";break;
        case "humanity": echo "#1E1B1D;";break;
        case "hot-sneaks": echo "#2C4359;";break;
        case "excite-bike": echo "#222222;";break;
        case "vader": echo "#EEEEEE;";break;
        case "dot-luv": echo "#D9D9D9;";break;
        case "mint-choc": echo "#FFFFFF;";break;
        case "black-tie": echo "#222222;";break;
        case "trontastic": echo "#FFFFFF;";break;
        case "swanky-purse": echo "#EFEC9F;";break;
        default: echo "#FFFFFF;";break;
    }

?>

}


a
{

<?php
    echo "\ncolor:";
    switch ($theme) {
        case "ui-lightness": echo "#333333;";break;
        case "sunny": echo "#383838;";break;
        case "ui-darkness": echo "#FFFFFF;";break;
        case "redmond": echo "#222222;";break;
        case "overcast": echo "#333333;";break;
        case "le-frog": echo "#FFFFFF;";break;
        case "flick": echo "#444444;";break;
        case "pepper-grinder": echo "#1F1F1F;";break;
        case "eggplant": echo "#FFFFFF;";break;
        case "cupertino": echo "#362B36;";break;
        case "dark-hive": echo "#FFFFFF;";break;
        case "south-street": echo "#312E25;";break;
        case "blitzer": echo "#333333;";break;
        case "humanity": echo "#1E1B1D;";break;
        case "hot-sneaks": echo "#2C4359;";break;
        case "excite-bike": echo "#222222;";break;
        case "vader": echo "#EEEEEE;";break;
        case "dot-luv": echo "#D9D9D9;";break;
        case "mint-choc": echo "#FFFFFF;";break;
        case "black-tie": echo "#222222;";break;
        case "trontastic": echo "#FFFFFF;";break;
        case "swanky-purse": echo "#EFEC9F;";break;
        default: echo "#FFFFFF;";break;
    }

?>

}


#header {
    padding:5px 10px;
    font-size:20px;
}
h1 {
    margin:0;
}
#nav {
    padding:5px 0px 0px 0px;
    height:30px;
}
#nav ul {
    margin:0;
    padding:0;
    list-style:none;
}
#nav li {
    display:inline;
    margin:0;
    padding:10px;
}
#main {
    float:left;
    width:680px;
    padding:10px;
    background-color: 

<?php
    //My own personal tweaking... 

    switch ($theme) {
        case "ui-lightness": echo "#FFFFFF;";break;
        case "sunny": echo "#FFFFD1;";break;
        case "ui-darkness": echo "#303030;";break;
        case "redmond": echo "#FFFFFF;";break;
        case "overcast": echo "#DDDDDD;";break;
        case "le-frog": echo "#3C7014;";break;
        case "flick": echo "#FFFFFF;";break;
        case "pepper-grinder": echo "#FFFEF3;";break;
        case "eggplant": echo "#514A58;";break;
        case "cupertino": echo "#FFFFFF;";break;
        case "dark-hive": echo "#141414;";break;
        case "south-street": echo "#FFFFF9;";break;
        case "blitzer": echo "#FFFFFF;";break;
        case "humanity": echo "#FFFFFF;";break;
        case "hot-sneaks": echo "#FFFFFF;";break;
        case "excite-bike": echo "#FFFFFF;";break;
        case "vader": echo "#262626;";break;
        case "dot-luv": echo "#252525;";break;
        case "mint-choc": echo "#342D27;";break;
        case "black-tie": echo "#FFFFFF;";break;
        case "trontastic": echo "#2b5500;";break;
        case "swanky-purse": echo "#584527;";break;
        default: echo "#FFFFFF;";break;
    }


?>
}

html, body
{
background-color:
<?php
    //My own personal tweaking... 

    switch ($theme) {
        case "ui-lightness": echo "#924400;";break;
        case "sunny": echo "#1D1401;";break;
        case "ui-darkness": echo "#000000;";break;
        case "redmond": echo "#003868;";break;
        case "overcast": echo "#797979;";break;
        case "le-frog": echo "#001D00;";break;
        case "flick": echo "#797979;";break;
        case "pepper-grinder": echo "#9B9B9B;";break;
        case "eggplant": echo "#000000;";break;
        case "cupertino": echo "#7A8993;";break;
        case "dark-hive": echo "#000000;";break;
        case "south-street": echo "#888476;";break;
        case "blitzer": echo "#680000;";break;
        case "humanity": echo "#672000;";break;
        case "hot-sneaks": echo "#000000;";break;
        case "excite-bike": echo "#959595;";break;
        case "vader": echo "#242424;";break;
        case "dot-luv": echo "#00000B;";break;
        case "mint-choc": echo "#000000;";break;
        case "black-tie": echo "#000000;";break;
        case "trontastic": echo "#3B7600;";break;
        case "swanky-purse": echo "#584527;";break;
        default: echo "#000000;";break;
    }


?>

}


h2 {
    margin:0 0 1em;
}
#sidebar {
    float:right;
    width:230px;
    padding:10px;
}
#footer {
    clear:both;
    padding:5px 5px;
}
#footer {
    font-size:12px;
    margin:0px;
    
}
* html #footer {
    height:1px;
}

.search_form
{
    float:right;
}

.gradient {
 background: #FFFFFF; /* for non-css3 browsers */
 filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#F0F0F0', endColorstr='#FFFFFF'); /* for IE 
*/
 background: -webkit-gradient(linear, left top, left bottom, from(#F0F0F0), to(#FFFFFF)); /* for webkit 
browsers */
 background: -moz-linear-gradient(top,  #43A2CA,  #f0f0f0); /* for firefox 3.6+ */ 
}


</style>
<style type="text/css">	
	.fg-button { outline: 0; margin:0 2px 0 0; padding: 0.2em 0.7em;
        text-decoration:none !important; cursor:pointer;
        position: relative; text-align: center; zoom: 1; }
	.fg-button .ui-icon { position: absolute; top: 50%; margin-top: -8px; left: 50%; margin-left: -8px; }
	
	a.fg-button { float:left; }
	
	/* remove extra button width in IE */
	button.fg-button { width:auto; overflow:visible; }
	
	
	.fg-button-icon-solo { display:block; width:8px; height:20px; width:20px; text-indent: -9999px; }	 
/* solo icon buttons must have block properties for the text-indent to work */	
	
	.fg-buttonset { float:left; }
	.fg-buttonset .fg-button { float: left; }
	.fg-buttonset-single .fg-button, 
	.fg-buttonset-multi .fg-button { margin-right: -1px;}
	
	.fg-toolbar { padding: .5em; margin: 0;   }
	.fg-toolbar .fg-buttonset { margin-right:1.5em; padding-left: 1px; }

</style>



</head>
<body>
    <?php

    if($success !== false)
    {
        echo "<div id='errorblock' class='ui-widget'>
				<div class='ui-state-highlight ui-corner-bottom' style='padding: 0pt 0.7em;'> 
					<p><span class='ui-icon ui-icon-info' style='float: left; 
margin-right: 0.3em;'></span> 
					$success</p>
				</div>

			</div>";
    }


    if($error !== false)
    {
        echo "<div id='errorblock' class='ui-widget'>
				<div class='ui-state-error ui-corner-bottom' style='padding: 0pt 0.7em;'> 
					<p><span class='ui-icon ui-icon-alert' style='float: left; 
margin-right: 0.3em;'></span> 
					$error</p>
				</div>

			</div>";
    }?>
<br>
<div class="ui-widget-content ui-helper-hidden"></div>
    
<div id = 'loginblock' title="<?php echo $title;?> Login" class="ui-helper-hidden">
    <form id="loginform" action="<?php echo $baseurl;?>" method="post">
    <br>
     <p align="center">Password: &nbsp;&nbsp;&nbsp;<input name="password" id="user_password" value=""  
type="password"
              onblur="this.style.backgroundColor='#ffffff'" onfocus="this.style.backgroundColor='#FFFCD0'"
              > 
     </p>
    </form>

</div>
<div id="doublewrap" class="ui-corner-all">
 <br>
 <div id="wrap">
         <div id="header" class="ui-dialog-titlebar ui-widget-header ui-corner-all
         ui-helper-clearfix"><?php
         echo "<a href='$baseurl'>$title</a>";
         if (isset($slogan))
         {
           if(trim($slogan) != "")
           {
            echo " | $slogan";
           }
         }

         
         ?>
         
		<a href="<?php echo $baseurl."?page=rss";?>" style="float:right;" class="fg-button 
ui-state-default
            fg-button-icon-solo  ui-corner-all" title="RSS">
            <span class="ui-icon ui-icon-signal-diag"></span> RSS</a>
            
                    <?php
         if($enableTwitter)
         {
            echo '		<a href="http://www.twitter.com/'.$twitterAccount.'"
            style="float:right;;" class="fg-button ui-state-default fg-button-icon-solo  ui-corner-all"
            title="Twitter"><img class="ui-icon " src="http://twitter-badges.s3.amazonaws.com/t_small-a.png"
                alt="Follow on Twitter" /></a>';

//                  <a style="float:right;border:none;" href="http://www.twitter.com/'.$twitterAccount.'">
//                <img style="border:none;" src="http://twitter-badges.s3.amazonaws.com/t_small-a.png"
//                alt="Follow on Twitter" /></a>';            
         }
?>
         </div>
         <div id="nav" class = "nav">
                 <ul style="float:left;">
                  <?php
                  
                echo "<li><a href='$baseurl'>Home</a></li>";
                  foreach($navbarlinks as $navlink)
                  {
                    echo "<li>$navlink</li>";
                  }
                  if($enableSiteContact)
                  {
                    echo "<li><a href='$baseurl?page=contact'>Contact</a></li>";
                    
                  }
                  if($enableFileBrowser)
                  {
                    echo "<li><a href='$baseurl?page=files'>Browse Files</a></li>";
                  }
                  if($enableThemeRotate)
                  {
                    
                    echo "<li><a href='$baseurl?theme=";
                    if($choosetheme+1 >= count($themes))
                    {
                        echo urlencode($themes[0]);
                    } else {
                        echo urlencode($themes[$choosetheme+1]);
                    }
                        
                    echo "'>Next Theme</a></li>";
                    
                  }
                                  
                  //http://twitter-badges.s3.amazonaws.com/t_small-a.png
                  ?>

                 </ul>
 
         <form name="search_form" class="search_form" action="<?php echo $baseurl;?>" method="get">
             <input id="s" class="text_input" type="text"
                    onblur="this.style.backgroundColor='#ffffff'" 
onfocus="this.style.backgroundColor='#FFFCD0'" name="s" />
             <input type="submit" align="middle" id="search-submit" value="Search" />
             <input id="searchsubmit" type="hidden" value="Search"/>
         </form>                        	
 
 
         </div>
         <div id="main" class="ui-corner-all">
          <?php
          
          $categories = array();
          
          
          if(($enableFileBrowser && ($pagerequest == "files"))
             || (($pagerequest == "files") && $loggedIn)
             )
          {

            
            if(isset($_REQUEST['directory']))
            {
                    //sneaky up directory not allowed.
                if(strpos($_REQUEST['directory'], "../") !== false)
                {
                    $directory = ".";
                } else {
                    $directory = $_REQUEST['directory'];
                }
                
            } else {
                $directory = ".";
            }
            
            $directory = trimDotsSlashes($directory);


            if($loggedIn)
            {
                ?>
    <form action="<?php echo $baseurl;?>" enctype="multipart/form-data" id="uploadform" method="post">
<div class="fg-toolbar ui-widget-header ui-corner-all ui-helper-clearfix">
    <input  style="float:left;"  type="file" name="uploadfile" size="10">
	<div class="fg-buttonset ui-helper-clearfix">
		<a id="uploadbutton" style="float:left;" href="#" class="fg-button ui-state-default 
fg-button-icon-solo  ui-corner-all" title="Upload">
            <span class="ui-icon ui-icon-circle-arrow-n"></span> Upload</a>
        <input type="hidden" name="directory" value="<?php echo $directory;?>">
	    <input type="hidden" name="page" value="files">

    </div>
        </form>
    <div style="float:right;">
        Disk size: <?php echo  format_bytes(disk_total_space("/"));?>&nbsp;&nbsp;|&nbsp;&nbsp;Disk Free: <?php 
echo format_bytes(disk_free_space("/"));?>
                (<?php echo floor(((disk_free_space("/") / disk_total_space("/")) * 100));?>%)
        </div>
    
</div>
         
         <?php
            }


            echo "<h2>Path: ";
            $pathbreakdown = explode("/", $directory);
            $pathcounter = 0;
            
            
            
            echo " <a href='$baseurl?page=files'>home</a>";
            
            
            
            
            for($i = 0; $i < count($pathbreakdown) && isset($_REQUEST['directory']); $i++)
            {
                echo " / <a href='$baseurl?page=files&directory=";
                
                for($j = 0; $j <= $i; $j++)
                {
                    echo trimDotsSlashes($pathbreakdown[$j]);
                    if($j < $i)
                    {
                        echo "/";
                    }
                    
                }
                echo "'>$pathbreakdown[$i]</a>";
            }
            
            echo "</h2>";

            
            if(trim($directory) == "")
            {
                $directory = ".";
            }
            $directoryContents = directoryContents($directory);
            
            echo "<table ";
            if($loggedIn)
            {
                echo "width='100%' ";
            } else {
                echo "width='75%' ";
            }
            echo " cellspacing='0' cellpadding='0' border='0'>";
            
            $rowcounter = 0 ;
            $highlightclass = "ui-state-highlight";
            foreach($directoryContents['directories'] as $dir)
            {
                $rowcounter++;
                echo "<tr style='font-size: 15pt;' ";
                if($rowcounter % 2)
                {
                    echo " class='$highlightclass' ";
                }

                $dir = trimDotsSlashes($dir);
                $newpath = trimDotsSlashes(trimDotsSlashes($directory)."/".trimDotsSlashes($dir));
                echo "><td><span class='ui-icon ui-icon-folder-collapsed'></span></td><td>".
                "<a href='$baseurl?page=files&directory=$newpath'>$dir</a>"
                ."</td><td></td>";
                
                if($loggedIn)
                {
                    echo "<td></td>";
                }
                echo "</tr>";
                
            }
            
            foreach($directoryContents['files'] as $file)
            {

                if(!$loggedIn)
                {
                    $extension = pathinfo($file, PATHINFO_EXTENSION);
                    if(stripos($filetypes, $extension) === false)
                    {
                        //don't display the file
                        continue;
                    }
                }


                $filesize = format_bytes(sprintf("%u", filesize($directory."/".$file)));
                

                $rowcounter++;
                echo "<tr style='font-size: 15pt;' ";
                if($rowcounter % 2)
                {
                    echo " class='$highlightclass' ";
                }

                $file = trimDotsSlashes($file);
                $findslash = substr($baseurl,0, strrpos($baseurl,"/")+1);
                
                echo "><td></td><td><a href='$findslash".$directory."/$file'>$file</a>
                </td><td>$filesize</td>";
                if($loggedIn)
                {
                    $rawfile = $directory."/".$file;
                    ?>
                    <td><div class="fg-buttonset  ui-helper-clearfix" style="float:right;">
		<a href="#" title="<?php echo $rawfile; ?>"  class="fg-button ui-state-default 
fg-button-icon-solo  ui-corner-all deletebutton" title="Delete">
            <span class="ui-icon ui-icon-trash"></span> Delete</a>
		<a href="#" title="<?php echo $rawfile; ?>" class="fg-button ui-state-default 
fg-button-icon-solo  ui-corner-all movebutton" title="Move">
            <span class="ui-icon ui-icon-arrow-4"></span> Move</a>
		<a href="#" title="<?php echo $rawfile; ?>" class="fg-button ui-state-default 
fg-button-icon-solo  ui-corner-all copybutton" title="Copy">
            <span class="ui-icon ui-icon-copy"></span> Copy</a>
            <!--
		<a href="#" title="<?php echo $rawfile; ?>"  class="fg-button ui-state-default 
fg-button-icon-solo
            ui-corner-all permissionsbutton" title="Permissions">
            <span class="ui-icon ui-icon-wrench"></span> Rename</a>
-->
	</div></td>

                <?php }
                echo "</tr>";
                
                
                
            }
            echo "</table>";
            
            
          }elseif ($loggedIn && ($pagerequest == "emailer"))
          {?><form action="<?php echo $baseurl; ?>" method="post"> 

<br>
<table id="contact">
    <tr>
        <td>Name: </td><td><input type="text" name="name" size="40" value="<?php echo $yourname;?>"></td>
    </tr><tr>
        <td>From Email: </td><td><input type="text" name="fromemail" size="40" value="<?php echo 
$email;?>"></td>
    </tr><tr>
        <td>To Email: </td><td><input type="text" name="toemail" size="40" ></td>
    </tr><tr>
        <td colspan="2"><br></td>
    </tr><tr>
        <td>Subject: </td><td><input type="text" name="subject" size="40" value=""></td>
   </tr><tr>
        <td colspan="2">Message: <br>
        <textarea name="message" cols="50" rows="10" ></textarea>
        </td>
    </tr>
   </tr><tr>
   <input type="hidden" name="internalemail" value="yes">
        <td colspan="2"><input type="submit" name="contact" value="Send Message">
        </td>
    </tr>
</table>
</form>
        
            <?php
            
            

          } elseif ($enableSiteContact && ($pagerequest == "contact"))
          {
            ?><form action="<?php echo $baseurl; ?>" method="post"> 
<br>
<table id="contact">
    <tr>
        <td>Name: </td><td><input type="text" name="name" size="40" value=""></td>
    </tr><tr>
        <td>Email: </td><td><input type="text" name="email" size="40" value=""></td>
    </tr><tr>
        <td colspan="2"><br></td>
    </tr><tr>
        <td>Subject: </td><td><input type="text" name="subject" size="40" value=""></td>
   </tr><tr>
        <td colspan="2">Message: <br>
        <textarea name="message" cols="50" rows="10" ></textarea>
        </td>
    </tr>
   </tr><tr>
        <td colspan="2"><input type="submit" name="contact" value="Send Message">
        </td>
    </tr>
</table>
</form>
        
            <?php
            
          } else {
            
            
            $contentCount = 0;
 
            $sentOlder = false;
            foreach ($content as $article)
            {
              
              $categories [] = $article['category']; 
  
  
              if(isset($_REQUEST['s']))
              {
                  $trimmed = trim($_REQUEST['s']); 
                  $searchresult = stripos($article['content'], $trimmed);
  
                  if($searchresult !== false)
                  {
                      //remove break and more
                      $article['content'] = str_replace(array('<!--break-->', '<!--more-->'),'', 
$article['content']); 
  
                      //highlight result
                      $article['content'] = str_ireplace($trimmed,
                          "<span class = 'ui-state-highlight'>".$trimmed."</span>", $article['content']);
                      
                      //truncate content to result
                      $searchresult = $searchresult - 50;
                      if($searchresult < 50)
                      {
                          $searchresult = 0;
                      }
                      
                      $article['content'] = substr($article['content'] ,
                          $searchresult, strlen($trimmed) + 150 ) . '<!--break-->';
                  } else {
                      continue;
                  }
                  
              }
  
  
              if(isset($_REQUEST['id']))
              {
                  if($_REQUEST['id'] != $article['permalink'])
                  {
                      continue;
                  }
                  
              }
  
              if(isset($_REQUEST['category']))
              {
                  if($_REQUEST['category'] != $article['category'])
                  {
                      continue;
                  }
              }
              
              if(!isset($_REQUEST['id']))
              {
                $readmore = strpos($article['content'], "<!--break-->");
                if($readmore === false)
                {
                    $readmore = strpos($article['content'], "<!--more-->");
                }
                
                if($readmore !== false)
                {
                    $article['content'] = substr($article['content'], 0, $readmore);
                    $article['content'] = $article['content']  .
                        "<br><br><span style='float:right;' class='ui-icon ui-icon-arrowthick-1-e'></span> " 
                        ."<a style='float:right;' href='$baseurl?id=".urlencode($article['permalink'])."'>Read 
more.</a>";
                        
                }

                $contentCount++;
                
              } else {
                
                
                $article['content'] = $article['content']  . "<br>";
                
                if($enableFacebookLike)
                {
                    $url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                    $article['content'] = $article['content']  . "<br>". 
                                //layout=button_count
                        '<iframe src="http://www.facebook.com/plugins/like.php?href=' .
                        $url.
                        '"scrolling="no" frameborder="0" style="border:none; width:450px; 
height:80px"></iframe>';
                }
        
              }
              
            $nextrequest = 0;
            if(isset($_REQUEST['next']))
            {
                $nextrequest = $_REQUEST['next'] + 1;
                
            }

            if($contentCount >= (($nextrequest + 1) * $postsFrontPage))
            {
                if($sentOlder == false)
                {
                    $sentOlder = true;
                    
                    echo "<a href='$baseurl?next=".($nextrequest + 1);
                    if(isset($_REQUEST['s']))
                    {
                        echo "&s=".$_REQUEST['s'];
                    }
                    if(isset($_REQUEST['category']))
                    {
                        echo "&category=".$_REQUEST['category'];
                    }
                    echo "'><button style='float:right;' class='ui-state-default ui-corner-all'
                        type='submit'>More Entries</button></a>";
                    
                    
                } else {
                    continue;
                    
                }

            } else {
                
                if(isset($_REQUEST['next']))
                {
                    if($contentCount < (($_REQUEST['next']) * $postsFrontPage))
                    {
                        continue;
                    }                
                }
                echo "<h2><a href='$baseurl?id=".urlencode($article['permalink'])."'>".$article['title'] . 
"</a>
                <span style=;float:right;'><small>".$article['date']."</small></span>
                </h2>".$article['content']
                . "<br>" . 
                '<span class="ui-icon ui-icon-folder-open" style="margin: 0 2px 0 2px; float:left;"></span>'
                ."<small>Filed under: <a 
href='$baseurl?category=".urlencode($article['category'])."'>".$article['category']."</a></small><hr>";
                
            }

              
              
             
            }
          }
          ?>
          </div>
         <div id="sidebar">
                 <h2 align="center">Categories</h2>
                 <ul style="position:relative;top:-10px;">
                    <?php
                    $categories = array_count_values($categories);
                    
                        foreach($categories as $name => $count)
                        {
                            echo "<li><a href='$baseurl?category=".urlencode($name)."'>$name</a> 
($count)</li>";
                        }
                    
                    ?>
                 </ul>
                 
                    
                    <?php if($enableLogin)
                    {
                      echo '<h2 align="center">Tools</h2>';  
                    }
                    ?>
                    
                 
                 
                 <ul style="position:relative;top:-10px;">
                  <?php
                  if($loggedIn)
                  {
                    echo "<li><a href='$baseurl?page=logout'>Logout</a></li>";

                  } elseif ($enableLogin)
                  {
                    echo "<li><a id='loginlink' href='$baseurl?page=login'>Login</a></li>";
                    
                  }
                  if($loggedIn)
                  {
                    echo "<li><a href='$baseurl?page=files'>File Manager</a></li>";
                    echo "<li><a href='$baseurl?page=emailer'>Emailer</a></li>";
                    //echo "<li><a href='$baseurl?page=shell'>Command Shell</a></li>";
                    echo "<li><a href='$baseurl?page=phpinfo'>phpinfo()</a></li>";
                    //echo "<li><a href='$baseurl?page=sql'>SQL</a></li>";
                    //echo "<li><a href='$baseurl?page=wordpress'>WordPress repair</a></li>";
                    //echo "<li><a href='$baseurl?page=drupal'>Drupal repair</a></li>";
                    //echo "<li><a href='$baseurl?page=drupal'>Joomla! repair</a></li>";
                    
                  }
                  ?>

                  </ul>
         </div>
         <br>
         <div id="footer"class="ui-widget ui-corner-bottom"><br>
            Copyright &copy; <?php echo date('Y'). " $copyright"; ?>, powered by <a 
href="http://ultrose.com/">Ultrose</a>
         </div>
 
 </div>
<br>
</div>
<br>
<?php

echo $GoogleAnalyticsCode;

?>
</body>
</html>
<?php
//functions...
function getBaseUrl()
{

    if(isset($_SERVER['HTTPS']))
    {
        $url = "http://";

       if($_SERVER['HTTPS'])
       {
            $url = "https://";
        }
       
    } else {
        $url = "http://";
    }
    
    $url = $url.$_SERVER['HTTP_HOST'];


    if(!isset($_SERVER['HTTPS']) && $_SERVER['SERVER_PORT'] !=  80)
    {
        $url = $url . ":" . $_SERVER['SERVER_PORT'];
        
    } 

    if(isset($_SERVER['HTTPS']) && $_SERVER['SERVER_PORT'] !=  443)
    {
        $url = $url . ":" . $_SERVER['SERVER_PORT'];
    }

    
    $url = $url .  $_SERVER['REQUEST_URI'];
    $url = str_replace("?". $_SERVER['QUERY_STRING'], "", $url);    
    return $url;
}

function searchContent ($contentArray)
{
 
}


function print_r_html($data,$return_data=false)
{
    $data = print_r($data,true);
    $data = str_replace( " ","&nbsp;", $data);
    $data = str_replace( "\r\n","<br>\r\n", $data);
    $data = str_replace( "\r","<br>\r", $data);
    $data = str_replace( "\n","<br>\n", $data);

    if (!$return_data)
        echo $data;
    else
        return $data;
}



function format_bytes($size) {
    $units = array(' Bytes', ' KiB', ' MiB', ' GiB', ' TiB');
    for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
    return round($size, 2).$units[$i];
}


function mailer($to, $fromname, $fromemail, $subject, $themessage)
{
    $headers = "From: $fromname <$fromemail>\r\n".
        'X-Mailer: PHP/' . phpversion();
    mail($to, $subject, ($themessage), $headers);
}


function directoryContents($directory)
{
    global $title, $slogan, $desciption, $yourname, $email, $content, $baseurl, $postsFrontPage, $filetypes;

    // open this directory
    $myDirectory = opendir($directory);
    
    // get each entry
    while($entryName = readdir($myDirectory)) {
        $dirArray[] = $entryName;
    }
    
    // close directory
    closedir($myDirectory);
    
    $directoryContents = array("files", "directories");
    $directoryContents['files'] = array();
    $directoryContents['directories'] = array();

    foreach($dirArray as $file)
    {
        if (substr($file, 0, 1) == ".")
        {
            continue;
            
        }
        $filetype = filetype($directory."/".$file);
        if($filetype == "dir")
        {
            $directoryContents['directories'][] = $file; 
            
        } else {
            $directoryContents['files'][] = $file;
            
        }
    }
    
    return $directoryContents;
    
}

function trimDotsSlashes($string)
{
    while(substr($string, 0, 1) == "." || substr($string, 0, 1) == "/")
    {
        $string = substr($string, 1);
    }
    
    while(substr($string, strlen($string) - 1, strlen($string)) == "/")
    {
        $string = substr($string, 0, strlen($string) - 1);
    }
    return $string;
    
}


function outputRSS()
{
   
    global $title, $slogan, $desciption, $yourname, $email, $content, $baseurl, $postsFrontPage;
    
    $allowedtags = '<p><ul><li><b><strong><ol><img><a><br><pre><img>';
//    $allowedtags = '<br>';

      header ("content-type: text/xml");
    echo '<?xml version="1.0" encoding="windows-1252"?>';
?><rss version="2.0">
  <channel>
    <title><?php echo "$title | $slogan";?></title>
    <description><?php echo $desciption;?></description>
    <link><?php echo $baseurl;?></link>
    <copyright>Copyright <?php echo $yourname;?></copyright>
    <language>en-us</language>
    <lastBuildDate><?php echo date('D, d M Y H:i:s T', strtotime ( "today")); ?></lastBuildDate>
    <pubDate><?php echo date('D, d M Y H:i:s T', strtotime ( "today")); ?></pubDate>
    <generator>http://ultrose.com/</generator>
<?php
    
    $pageCounter = 0 ;
    
    foreach($content as $item)
    {
        if($pageCounter >= $postsFrontPage)
        {
            break;
        }
        $pageCounter++;
        
        
        ?>

    <item>
      <title><?php echo $item['title'];?></title>
      <description><?php echo htmlentities(strip_tags($item['content'], $allowedtags));?></description>

      <link><?php echo "$baseurl?id=".urlencode($item['permalink']);?></link>
      <pubDate><?php echo date('D, d M Y H:i:s T', strtotime ( $item['date'])); ?></pubDate>
      <guid isPermaLink="true"><?php echo "$baseurl?id=".urlencode($item['permalink']);?></guid>
    </item>
            
        
        <?php
    }
    ?>
    
  </channel>
</rss>    

<?php
}



?>


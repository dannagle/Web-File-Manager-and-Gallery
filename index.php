<?php
/*
Ultrose File Manager

Installation:

1. Save this file.
2. Upload to your web server.

*/

$enableLogin = 1; //set to 0 to secure your site.
$enableFileBrowser = 1; //set to 0 to disable public file browsing.
$enableSiteContact = 1; //set to 0 to disable.
$enableThemeRotate = 1; //set to 0 to disable.


/*
Ultrose is copyright and wholly owned by Dan Nagle (http://dannagle.com/).
It is Dual-Licensed under GPLv3 or MIT.


===========================MIT LICENSE================================================

Copyright (c) 2011 Dan Nagle (http://dannagle.com)

Permission is hereby granted, free of charge, to any person obtaining a copy of this
software and associated documentation files (the "Software"), to deal in the Software
without restriction, including without limitation the rights to use, copy, modify,
merge, publish, distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to the following
conditions:

The above copyright notice and this permission notice shall be included in all copies
or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A
PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.


===========================GPLv3================================================

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
    

For brevity, the GPL text is not included.
You may look here for the text: http://www.gnu.org/licenses/gpl-3.0.txt


*/

global $title, $slogan, $desciption, $yourname,
    $email, $baseurl, $filetypes;

// Basic configuration 
$title = "Ultrose";
$slogan = "File Manager";
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
//Use a trailing slash.
//Example: "http://example.com/ultrose/"; 
//Example: "http://ultrose.com/"; 
$baseurl = getBaseUrl();


//public file browser does not allow uploads
 
//browseable file types (when not logged in)
$filetypes = "7z tar gz txt zip exe dmg pdf doc docx
            xls xlsx mp3 mpg ogg flv msi wav png gif
            jpg jpeg avi mov mp4";


//If you wish to customize date listings, here is the PHP date string.
$datetimestring = "M j, Y";




/*
 
Copy-paste then modify nav template to add your own links.
 
$navbarlinks[] = <<<ULTROSECONTENT
Your link goes here.
ULTROSECONTENT;

Your content starts and stops with the key ULTROSECONTENT
 
*/


$navbarlinks[] = <<<ULTROSECONTENT
<a href="http://ultrose.com/">Ultrose</a>
ULTROSECONTENT;



//22 available Google-hosted themes. Comment them out to keep them out of rotation
//$themes[] = "ui-lightness";
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
    $loggedIn = false;
}


if(stripos($theme, "random") !== false)
{
        //crude method to "seed" the random.
        //don't want all our installs to show the same "random" theme each day, do we?
    $titlehashhex = md5($title);
        //chop it down a bit to ease memory/logic.
    $titlehashhex = substr($titlehashhex, 0, floor(strlen($titlehashhex) / 3));
    $themeseed = (hexdec($titlehashhex));


    $choosetheme = abs(($themeseed + date('j')) % (count($themes)));

    $theme = $themes[$choosetheme];
} else {
    $choosetheme = array_search($theme, $themes);
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
    
} else {
    $pagerequest = "files";
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

if($pagerequest == "logout")
{
    setcookie("password", "", time()-1000);  // force expire
    $loggedIn = false;
    $success = "You are now logged out."; 
    $pagerequest = "files";
          
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
<META NAME="DESCRIPTION" CONTENT="<?php echo $desciption; ?>">
<META NAME="Generator" CONTENT="Ultrose 1.1">
    
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js" type="text/javascript"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/jquery-ui.min.js" 
type="text/javascript"></script>

<script type="text/javascript">


$(document).ready(function(){

    $('#uploadbutton').click(function() {
      $('#uploadform').submit();
    });

	$(".successerrorclose").click(function(){
		$(".errorblock").slideUp();
		return false;

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
		hide: 'fade',
        
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

    	//hover states on the static widgets
	$('.fg-button-icon-solo, .fg-button').hover(
		function() { $(this).addClass('ui-state-hover'); }, 
		function() { $(this).removeClass('ui-state-hover'); }
	);

    
    
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

.errorblock {
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

<?php

//My own personal tweaking... 

$colortweakwrapbg = "#FFFFFF;";
$colortweakwrapcolor = "#FFFFFF;";
$colortweakmainbg =  "#FFFFFF;";
$colortweakbodybg =  "#000000;";

    switch ($theme) {
        case "ui-lightness":
            $colortweakwrapbg = "#EEEEEE;";
            $colortweakwrapcolor = "#333333;";
            $colortweakmainbg =  "#FFFFFF;";
            $colortweakbodybg =  "#924400;";
            break;
        case "sunny":
            $colortweakwrapbg = "#FEEEBD;";
            $colortweakwrapcolor = "#383838;";
            $colortweakmainbg =  "#FFFFD1;";
            $colortweakbodybg =  "#1D1401;";
            break;
        case "ui-darkness":
            $colortweakwrapbg = "#252525;";
            $colortweakwrapcolor = "#FFFFFF;";
            $colortweakmainbg =  "#303030;";
            $colortweakbodybg =  "#000000;";
            break;
        case "redmond":
            $colortweakwrapbg = "#edf3f3;";
            $colortweakwrapcolor = "#222222;";
            $colortweakmainbg =  "#FFFFFF;";
            $colortweakbodybg =  "#003868;";
            break;
        case "overcast":
            $colortweakwrapbg = "#C9C9C9;";
            $colortweakwrapcolor = "#333333;";
            $colortweakmainbg =  "#DDDDDD;";
            $colortweakbodybg =  "#797979;";
            break;
        case "le-frog":
            $colortweakwrapbg = "#285C00;";
            $colortweakwrapcolor = "#FFFFFF;";
            $colortweakmainbg =  "#3C7014;";
            $colortweakbodybg =  "#001D00;";
            break;
        case "flick":
            $colortweakwrapbg = "#eeeeee;";
            $colortweakwrapcolor = "#444444;";
            $colortweakmainbg =  "#FFFFFF;";
            $colortweakbodybg =  "#797979;";
            break;
        case "pepper-grinder":
            $colortweakwrapbg = "#ECEADF;";
            $colortweakwrapcolor = "#1F1F1F;";
            $colortweakmainbg =  "#FFFEF3;";
            $colortweakbodybg =  "#9B9B9B;";
            break;
        case "eggplant":
            $colortweakwrapbg = "#3D3644;";
            $colortweakwrapcolor = "#FFFFFF;";
            $colortweakmainbg =  "#514A58;";
            $colortweakbodybg =  "#000000;";
            break;
        case "cupertino":
            $colortweakwrapbg = "#F2F5F7;";
            $colortweakwrapcolor = "#362B36;";
            $colortweakmainbg =  "#FFFFFF;";
            $colortweakbodybg =  "#7A8993;";
            break;
        case "dark-hive":
            $colortweakwrapbg = "#3c3c3c;";
            $colortweakwrapcolor = "#FFFFFF;";
            $colortweakmainbg =  "#141414;";
            $colortweakbodybg =  "#000000;";
            break;
        case "south-street":
            $colortweakwrapbg = "#eeebd5;";
            $colortweakwrapcolor = "#312E25;";
            $colortweakmainbg =  "#FFFFF9;";
            $colortweakbodybg =  "#888476;";
            break;
        case "blitzer":
            $colortweakwrapbg = "#eeebd5;";
            $colortweakwrapcolor = "#333333;";
            $colortweakmainbg =  "#FFFFFF;";
            $colortweakbodybg =  "#680000;";
            break;
        case "humanity":
            $colortweakwrapbg = "#F4F0EC;";
            $colortweakwrapcolor = "#1E1B1D;";
            $colortweakmainbg =  "#FFFFFF;";
            $colortweakbodybg =  "#672000;";
            break;
        case "hot-sneaks":
            $colortweakwrapbg = "#e6e9ec;";
            $colortweakwrapcolor = "#2C4359;";
            $colortweakmainbg =  "#FFFFFF;";
            $colortweakbodybg =  "#000000;";
            break;
        case "excite-bike":
            $colortweakwrapbg = "#EEEEEE;";
            $colortweakwrapcolor = "#222222;";
            $colortweakmainbg =  "#FFFFFF;";
            $colortweakbodybg =  "#959595;";
            break;
        case "vader":
            $colortweakwrapbg = "#121212;";
            $colortweakwrapcolor = "#EEEEEE;";
            $colortweakmainbg =  "#262626;";
            $colortweakbodybg =  "#242424;";
            break;
        case "dot-luv":
            $colortweakwrapbg = "#2c2c2c;";
            $colortweakwrapcolor = "#D9D9D9;";
            $colortweakmainbg =  "#252525;";
            $colortweakbodybg =  "#000000;";
            break;
        case "mint-choc":
            $colortweakwrapbg = "#3d2f25;";
            $colortweakwrapcolor = "#FFFFFF;";
            $colortweakmainbg =  "#342D27;";
            $colortweakbodybg =  "#000000;";
            break;
        case "black-tie":
            $colortweakwrapbg = "#e6e6e6;";
            $colortweakwrapcolor = "#222222;";
            $colortweakmainbg =  "#FFFFFF;";
            $colortweakbodybg =  "#000000;";
            break;
        case "trontastic":
            $colortweakwrapbg = "#1e3c00;";
            $colortweakwrapcolor = "#FFFFFF;";
            $colortweakmainbg =  "#2b5500;";
            $colortweakbodybg =  "#3B7600;";
            break;
        case "swanky-purse":
            $colortweakwrapbg = "#443113;";
            $colortweakwrapcolor = "#EFEC9F;";
            $colortweakmainbg =  "#584527;";
            $colortweakbodybg =  "#584527;";
            break;
        default:
            $colortweakwrapbg = "#FFFFFF;";
            $colortweakwrapcolor = "#FFFFFF;";
            $colortweakmainbg =  "#FFFFFF;";
            $colortweakbodybg =  "#000000;";
            break;
    }

?>
#wrap, #doublewrap
{
    background-color:  <?php echo $colortweakwrapbg;?>;
    color:  <?php echo $colortweakwrapcolor;?>;
}


a
{

<?php
    echo "\ncolor: $colortweakwrapcolor";
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
    width:910px;
    padding:10px;
    background-color: <?php echo $colortweakmainbg;?>;
}

html, body
{
    margin:0;
    padding:0;
    color:#000;
    background-color: <?php echo $colortweakbodybg;?>;

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
        echo "<div class='errorblock' class='ui-widget'>
				<div class='ui-state-highlight ui-corner-bottom' style='padding: 0pt 0.7em;'> 
					<p><span class='ui-icon ui-icon-info' style='float: left; 
margin-right: 0.3em;'></span> 
					$success
                    <br><a href='#' class='ui-icon ui-icon-circle-close successerrorclose' style='float: right;'></a>

                    </p>
				</div>

			</div>";
    }


    if($error !== false)
    {
        echo "<div class='errorblock' class='ui-widget'>
				<div class='ui-state-error ui-corner-bottom' style='padding: 0pt 0.7em;'> 
					<p><span class='ui-icon ui-icon-alert' style='float: left; 
margin-right: 0.3em;'></span> 
					$error
                    <br><a href='#' class='ui-icon ui-icon-circle-close successerrorclose' style='float: right;'></a>
                    </p>
				</div>

			</div>";
    }?>
<br>
    
<div class="ui-widget-content ui-helper-hidden"></div>
    
<div id = 'loginblock' title="<?php echo $title;?> Login" class="ui-helper-hidden">

<?
    if($enableLogin)
    {
?>        
    <form id="loginform" action="<?php echo $baseurl;?>" method="post">
    <br>
     <p align="center">Password: &nbsp;&nbsp;&nbsp;<input name="password" id="user_password" value=""  
type="password"
              onblur="this.style.backgroundColor='#ffffff'" onfocus="this.style.backgroundColor='#FFFCD0'"
              > 
     </p>
    </form>
<?        
    } else {
        ?><p align="center">Login disabled.</p><?        
    }
?>

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
         

		<a
           <?
           if($loggedIn)
           {
                echo ' href="?page=logout" ' ;
           } else {
                echo ' id="loginlink" href="#"  ' ;
           }
           
           ?>
           style="float:right;" class="fg-button 
ui-state-default
            fg-button-icon-solo  ui-corner-all" title="RSS">
            <span class="ui-icon ui-icon-person"></span> Login</a>

         
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
                  ?>

                 </ul>
 
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
</form><?php
            
 
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
            
                echo "<h2>Public Browsing Disabled.</h2> Log in to see files.<hr>";
          }
          ?>
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
    global $title, $slogan, $desciption, $yourname, $email, $baseurl, $filetypes;

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




?>


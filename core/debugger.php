
<html>
    <head>
        <title>Debugger | Vibius Framework</title>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
        <style>
            .logo{
                position:absolute;
                top:300px;
                left:500px;
                font-size:30px;
                color:rgba(185, 185, 185, 0.87);
            }
            html,body{
                font-family: 'Open Sans', sans-serif;
                background:#f7f7f7;
                margin:0;
                padding:0;
                width:1200px;
                height:100%;
            }
            .sidebar{
                //padding-left:10px;
                //padding-right:10px;
                background:#2a2a2a;
                float:left;
                width:260px;
                color:#efefef;
                height:100%;
                overflow:scroll;
            }
            .side-block{
                font-size:13px;
                margin-top:5px;
                padding:9px 10px 9px 10px;
                background:#1c1c1c;
                width:229px;
                max-height:200px;
                overflow:scroll;
            }
            .content{
             width:900px;
             float:left;
             padding-left:10px;
            }
            .heading >h1{
            color:#212020;
            font-size:22px;
             
            }
            .heading > a{
                color:#3e89c3;
            }
            .heading >p{
            font-size:14px;
            }
            .code{
                overflow:scroll;
                font-size:13px;
                padding:10px;
                min-width:900px;
                box-shadow:inset 0px 0px 2px #333;
            }
            .ln{
            display:inline-block;
            width:30px;
            margin-top:0.5px;
                padding:1px;
            }
            .l{
            margin:0;
            }
            .mark{
            color:#fa4f4f;
                font-weight:bold;
           
            }
                        ::-webkit-scrollbar-corner {
                        background: rgba(0, 0, 0, 0.1);
                        }
                        ::-webkit-scrollbar
						{
						  width: 12px;  /* for vertical scrollbars */
						  height: 12px; /* for horizontal scrollbars */
						}

						::-webkit-scrollbar-track
						{
						  background: #1c1c1c;
						}

						::-webkit-scrollbar-thumb
						{
						  background: #000;
						}
        </style>
    </head>
    <body>
       
        <div class="sidebar">
            <div class="side-block">
                <p>POST:</p>
                <pre>
<?php print_r($_POST);?></pre>
            </div>
            <div class="side-block">
                 <p>GET:</p>
                <pre>
<?php print_r($_GET);?></pre>
            </div>
            <div class="side-block">
                <p>COOKIE:</p>
                <pre>
<?php print_r($_COOKIE);?></pre>
            </div>
            <div class="side-block">
                <p>SESSION:</p>
                <pre>
<?php print_r($_SESSION);?></pre>
            </div>
        </div>
        <div class="content">
            <div class="heading">
                <h3>Something went wrong</h3>
                <p><?=$message?>, at line <i><?=$wline?> </i> <span style="padding-left:3px">in</span> file: <?=$file?></p>
                
            </div>
            <div class="code">
<?php
if(file_exists($file)){
    $handle = fopen($file, "r");
}else{
    $handle = false;
    $file = $GLOBALS['debugger_template'];
    if(file_exists($file)){
        $handle = fopen($file,'r');
    }else{
        $handle = false;
    }
}
if ($handle) {
    $ln = 0;
    while (($line = fgets($handle)) !== false) {
        $ln = $ln+1;
        $min = $wline-10;
        $max = $wline+10;
        if($ln >= $min && $ln <= $max){
            if($ln == $wline){
                echo "<p class='l mark'><b class='ln'> $ln </b>     ".htmlspecialchars($line).'</p>';
            }else{
            echo "<p class='l'><b class='ln'> $ln </b>     ".htmlspecialchars($line).'</p>';
            }
        }
        
    }
fclose($handle);
} else {
    // error opening the file.
} 

?>
            </div>
        </div>
    </body>
</html>


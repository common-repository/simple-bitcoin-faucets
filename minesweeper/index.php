<html> 
<head> 
<meta charset='UTF-8'>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bitcoin Minesweeper crypto.mygamesonline.org
</title>
	<link rel="icon" href="favicon.ico" type="image/x-icon">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <link rel="stylesheet" href="css/minesweeper.css" type="text/css"/>
    <link rel="stylesheet" href="css/smoothness/jquery-ui-1.9.2.custom.min.css" type="text/css"/>
	<script src="js/MineSweeper.js" type="text/javascript"></script>
	
	<link rel="stylesheet" href="/blurt.min.css">
	<script src="/blurt.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">	
	<script src="/wme_bclick.js"></script>
	<link rel="stylesheet" href="/wme_bclick.css">

	
<link rel="stylesheet" href="/wme_rfsr.css">	
<script src="/wme_rfsr.js"></script>
<script src="/wme_rfsr_cfg.js"></script>

</head>

<body>


<style>
.flexblock{
display: flex;
flex-direction: row;
flex-wrap: wrap;
justify-content: center;
align-items: center;
align-items: start;
border:0px dotted magenta; /*remove after debugging*/
}
/* only for readability, replace with read ads*/
.h_ad_placeholder{ /*horizontal*/
width:728px; height:90px; border:0px dotted grey;
}
.v_ad_placeholder{ /*vertical*/
width:160px; height:600px; border:0px dotted grey;
}
</style>
<style>
div #trof_text
{
position1: relative;
float1: left;
top: 50%;
left1: 50%;
transform1: translate(-50%, -50%);
color:red;
font-size: 170%;
cursor1:pointer;
width1:124px;
margin:5px;
}
</style>

<div style="background-color:lightyellow;color:red;position:sticky;top:0;">
Play also 
<a href='../lines.php'>Bitcoin Lines</a>
<a href='/blockrain/'>Bitcoin BlockRain</a>  
<a href='/2048/'>Bitcoin 2048</a>
<a href='../tetris.php'>Bitcoin Tetris</a>
<a href='../arcanoid.php'>Bitcoin Arcanoid</a>
<div style='float:right;'><a href='../10/'>Bitcoin Faucets</a></div>
</div>


<div class="container">
<!--
<iframe src="//www.youtube.com/embed/ijp5Ig9AE2A" 
frameborder="0" allowfullscreen class="video"></iframe>

</div>
<style>
.container {
    position: relative;
    width: 100%;
    height: 0;
    padding-bottom: 56.25%;
}
.video {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
</style>
-->


<!-- top ads start -->
<?php include_once($_SERVER['DOCUMENT_ROOT']."/_top_ads.php"); ?>
<!-- top ads end -->



<!-- center block start-->
<div id='center_block' class='flexblock' >
<!-- left ads start -->
<?php include_once($_SERVER['DOCUMENT_ROOT']."/_left_ads.php"); ?>
<!-- left ads end -->
<!-- faucet block start -->
<div id='faucet_wrap' class='flexblock' style='width:450px; border:0px outset green;'>

<div id='wmexp-faucet-TO-BE' style='display:none;width:600px;min-height:600px; border:green; background-color:lightgreen;'></div>


<!-- -->
<div id="minesweeper"></div>

<!-- -->
</div>
<!-- faucet block end -->
<!-- right ads start -->
<?php include_once($_SERVER['DOCUMENT_ROOT']."/_right_ads.php"); ?>
<!-- right ads end -->
</div>
<!-- center block end -->

<!-- bottom ads start -->
<?php include_once($_SERVER['DOCUMENT_ROOT']."/_bottom_ads.php"); ?>
<!-- bottom ads end -->

<script>
    jQuery(function ($) {
        // set a global instance of Minesweeper
        minesweeper = new MineSweeper();

        // init the (first) game
        minesweeper.init();
    });
</script>

<script>

t_reward = function(level)
{
	var script = document.createElement('script');
	document.querySelector('#minesweeper').style.display = 'none';
	document.querySelector('#wmexp-faucet-TO-BE').style.display = 'block';
	
	if(level == 'beginner') 
	{
		document.getElementById('wmexp-faucet-TO-BE').id = 'wmexp-faucet-123640';
		script.src = 'https://wmexp.com/faucet/123640/';	
	}
	if(level == 'intermediate') 
	{
		document.getElementById('wmexp-faucet-TO-BE').id = 'wmexp-faucet-123641';
		script.src = 'https://wmexp.com/faucet/123641/';	
	}
	if(level == 'expert') 
	{
		document.getElementById('wmexp-faucet-TO-BE').id = 'wmexp-faucet-123642';
		script.src = 'https://wmexp.com/faucet/123642/';	
	}	
	document.head.appendChild(script); 	
}

var trof_ingame = false;
var fs = "1111:01|01|01|01*011|110:010|011|001*110|011:001|011|010*111|010:01|11|01:010|111:10|11|10*11|11*010|010|011:111|100:11|01|01:001|111*01|01|11:100|111:11|10|10:111|001", now = [3,0], pos = [4,0];
var gP = function(x,y) { return document.querySelector('[data-y="'+y+'"] [data-x="'+x+'"]'); };
var draw = function(ch, cls) {
    var f = fs.split('*')[now[0]].split(':')[now[1]].split('|').map(function(a){return a.split('')});
    for(var y=0; y<f.length; y++)
        for(var x=0; x<f[y].length; x++)
            if(f[y][x]=='1') {
                if(x+pos[0]+ch[0]>9||x+pos[0]+ch[0]<0||y+pos[1]+ch[1]>19||gP(x+pos[0]+ch[0],y+pos[1]+ch[1]).classList.contains('on')) return false;
                gP(x+pos[0]+ch[0], y+pos[1]+ch[1]).classList.add(cls!==undefined?cls:'now');
            }
    pos = [pos[0]+ch[0], pos[1]+ch[1]];
}
var deDraw = function(){ if(document.querySelectorAll('.now').length>0) deDraw(document.querySelector('.now').classList.remove('now')); }
var check = function(){
	for(var i=0; i<20; i++)
		if(document.querySelectorAll('[data-y="'+i+'"] .brick.on').length == 10) 
			return check(roll(i), document.querySelector('#result').innerHTML=Math.floor(document.querySelector('#result').innerHTML)+10);
};
var roll = function(ln){ if(false !== (document.querySelector('[data-y="'+ln+'"]').innerHTML = document.querySelector('[data-y="'+(ln-1)+'"]').innerHTML) && ln>1) roll(ln-1); };


</script>




</body>

</html>

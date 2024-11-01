<html> 
<head> 
<meta charset='UTF-8'>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bitcoin 2048 crypto.mygamesonline.org
</title>
	<link rel="icon" href="favicon.ico" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <link rel="stylesheet" href="css/jquery.2048.css" />
	
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery.2048.js"></script>	
	
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
body{
	margin:0;
	padding:25px;
	font-family:Arial;
	font-size:14px;
}
</style>
<div style="background-color:lightyellow;color:red;position:sticky;top:0;">
Play also 
<a href='../lines.php'>Bitcoin Lines</a> 
<a href='/blockrain/'>Bitcoin BlockRain</a>  
<a href='/minesweeper/'>Bitcoin Minesweeper</a> 
<a href='../tetris.php'>Bitcoin Tetris</a>
<a href='../arcanoid.php'>Bitcoin Arcanoid</a>
<div style='float:right;'><a href='../10/'>Bitcoin Faucets</a></div>
</div>

<div class="main_container">



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
<div style='width:90%;'>
How to play: Use your arrow keys to move the tiles. 
When two tiles with the same number touch, they merge into one. 
<hr>
</div>
<center>
<div class="2048container text-center" id="2048">
</div>
</center>
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
    $(document).ready(function () {
        $("#2048").init2048();
    });
</script>


<script>
t_reward = function(score)
{
//blurt("Score: " + score , "Here is your bonus...");
blurt({
    title: "Score: " + score,
    text: 'Congratulations!',
    type: 'success',
    okButtonText: 'Get the bonus',
    escapable: true
});

	var script = document.createElement('script');
	document.querySelector('.container').style.display = 'none';
	document.querySelector('#wmexp-faucet-TO-BE').style.display = 'block';
	
	if(score < 256) 
	{
		document.getElementById('wmexp-faucet-TO-BE').id = 'wmexp-faucet-123650';
		script.src = 'https://wmexp.com/faucet/123650/';	
	}
	if(score == 256)  
	{
		document.getElementById('wmexp-faucet-TO-BE').id = 'wmexp-faucet-123651';
		script.src = 'https://wmexp.com/faucet/123651/';	
	}
	if(score == 512) 
	{
		document.getElementById('wmexp-faucet-TO-BE').id = 'wmexp-faucet-123652';
		script.src = 'https://wmexp.com/faucet/123652/';	
	}	

	if(score == 1024) 
	{
		document.getElementById('wmexp-faucet-TO-BE').id = 'wmexp-faucet-123653';
		script.src = 'https://wmexp.com/faucet/123653/';	
	}	
	
	if(score == 2048) 
	{
		document.getElementById('wmexp-faucet-TO-BE').id = 'wmexp-faucet-123654';
		script.src = 'https://wmexp.com/faucet/123654/';	
	} 
	
	document.head.appendChild(script); 	
}


</script>





</body>

</html>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<META HTTP-EQUIV="Content-Type" content="text/html; charset=utf-8" />
<META HTTP-EQUIV="Content-language" content="ru">
<title><?=(!empty($title)?$title.'  :: ':'')?>Мой-Твиттер</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/jquery-ui.min.js?ver=1.8.12"></script>
<script language="javascript">var BASEURL = '<?=BASEURL?>';</script>
<script type="text/javascript" src="<?=BASEURL?>js/main.js"></script>
<style>
.hand{cursor:pointer;}
.user_small_img{
	margin: 0px 4px;
}
.twitt{
	margin: 4px 0px 8px 0px;
	border: #EAEAEA 1px solid;
	padding:4px;	
}
.left{float:left;}
.right{float:right;}
.layout_13{ width: 250px; }
.layout_23{ width: 500px; }
.layout_12{ width: 375px; }
body{
	background-color: #c0deed;
	font-family: Verdana, Geneva, sans-serif;
	font-size: 12px;
	color: #666;
	margin: 2em 0px 6em;
}
form {
	padding: 6px 22px 0px;	
}
form label input, form label textarea {
	width: 100%;
	margin: 3px 10px 3px 0px;
}
#content{
	border: #b3b3b3 solid 1px;
	background-color: #fff;
	padding: 0 10px 10px 10px;
}

#navbar{
	margin: 15px 0 0 0;	
}
#navbar span{
	border: #b3b3b3 solid 1px;
	background-color: #fff;
	padding: 2px 4px;
	margin: 0 2px;
}
#navbar span a{
	color: #000;
	text-decoration:none;
	font-size: 115%;
}
h1{
	font-size: 135%;
	font-weight: bold;
	color: #333;
	width:100%;
	border-bottom:#b3b3b3 1px solid;
	padding: 10px 5px 0px 0px;
}

hr{
	border: 0;
	width:100%;
	border-bottom:#b3b3b3 1px solid;
	padding-top: 10px;
}

.result_table td{
	border: #CCCCCC solid 1px;
	text-align:center;
	padding: 2px 20px;
}
.result_table th{
	border-top: #333 solid 1px;
	border-bottom: #333 solid 1px;
	text-align:center;
	padding: 2px 20px;
	background:#EFEFEF;
}
.ok{
	font-size:115%;
	font-weight: bold;
	color: #000;
	background-color:#EBFFDD;
	border: 1px solid #060;
	margin: 20px 0;
	padding: 10px;
}
.info{
	font-size:115%;
	font-weight: bold;
	color: #000;
	background-color:#edfcfd;
	border: 1px solid #006;
	margin: 20px 0;
	padding: 10px;
}
.error{
	font-size:115%;
	font-weight: bold;
	color: #000;
	background-color:#FFC;
	border: 1px solid #F90;
	margin: 20px 0;
	padding: 10px;
}
.button{
	border: #b3b3b3 solid 1px;
	background-color: #fff;
	padding: 2px 6px;
	margin: 0 2px;
	text-decoration:none;
	color:#000;
	display: inline-block;
}

.progress{
	display: inline-block;

	margin: 1px 5px;
	border: 1px solid #333;
	width: 140px;
}
.progress > .bar{
		display: inline-block;
	height: 6px;
	background: #ccc;
	float:left;
	
}

.unactive > td{
	color: #ccc;
}
td > a{
	color: #333;
	text-decoration:none;
}

div.pagination { text-align:center;}
div.pagination div.next-prev span,
div.pagination div.next-prev a,
div.pagination div.pages a,
div.pagination div.pages span{
	display: inline-block;
	margin: 5px 2px 0px 2px;
}
div.pagination span{
	color:#999;
}
div.pagination a{
	border: #b3b3b3 solid 1px;
	background-color: #fff;
	padding: 2px 6px;
	text-decoration:none;
	color:#000;
}
div.pagination a.active,
div.pagination a:hover{
	border: #333 solid 1px;
	background-color: #f6f6f6;
	padding: 2px 6px;
	text-decoration:none;
	color:#000;
	top: -2px;
	position:relative;
}


.twitt:hover {
	background:#f6fbfd;
	
}
.twitt:hover > .hide-meta a {
	display: inline-block;
}
.hide-meta, .hide-meta a { color: #999;font-size: 11px; text-decoration:none; height:15px; vertical-align:bottom; }
.hide-meta div { margin: 4px 0px 2px 0px; }
.hide-meta a { display:none; }
.hide-meta a:hover{	color:#444;	}

.hide-meta .delete-action i {
text-indent: -99999px;
outline: none;
background: transparent url(/img/sprite-icons.png) no-repeat;
width: 15px;
height: 15px;
margin: 0 2px 0 6px;
display: block;
display: inline-block;
vertical-align: baseline;
position: relative;
background-position: -112px 3px;
}
.hide-meta .delete-action:hover i{
	background-position: -128px 3px;
}



/*---- clearfix ----*/
.clearfix:after {content: "."; display: block; clear: both; visibility: hidden; line-height: 0; height: 0;}
.clearfix {display: inline-block;}
html[xmlns] .clearfix {display: block;}
* html .clearfix {height: 1%;}

</style>
</head>
<body>
<table width="790 px" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><a href="<?=BASEURL?>"><img src="<?=BASEURL?>img/logo.png" /></a></td>
  </tr>
  <tr>
    <td align="center" valign="middle" nowrap="nowrap">

    <div id="navbar">
<?php if ( $_SESSION['login'] ): ?>
    	<span><a href="<?=BASEURL?>flow">Поток</a></span>
		<span><a href="<?=BASEURL?>users/profile/">Профиль</a></span>
		<span><a href="<?=BASEURL?>users/logout/">Выйти</a></span>
<?php else: ?>
		<span><a href="<?=BASEURL?>users/login/">Войти</a></span>
<?php endif; ?>
     </div>
        </td>
  </tr>
  <tr>
    <td><div id="content"><?=$content?>
    <div class="clearfix"></div>
    </div></td></tr></table></body></html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<META HTTP-EQUIV="Content-Type" content="text/html; charset=utf-8" />
<META HTTP-EQUIV="Content-language" content="ru">
<title><?=(!empty($title)?$title.'  :: ':'')?>Мой-Твиттер</title>
<link rel="stylesheet" href="<?=BASEURL?>css/reset.css" type="text/css" />
<link rel="stylesheet" href="<?=BASEURL?>css/style.css" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/jquery-ui.min.js?ver=1.8.12"></script>
<script language="javascript">var BASEURL = '<?=BASEURL?>';</script>
<script type="text/javascript" src="<?=BASEURL?>js/main.js"></script>
</head>
<body>

<div id="header">
<a href="<?=BASEURL?>"><img src="<?=BASEURL?>img/logo.png" /></a>
</div>
<div id="wrapper">
	<ul id="navbar">
        <?php if ( $_SESSION['login'] ): ?>
        		<?php if ( isset( $tabs ) ) :
					foreach ( $tabs as $link => $name ): ?>
						<li><a href="<?=BASEURL.$link?>"<?=($page==$link?' class="active"':'')?>><?=$name?></a></li>
					<?php endforeach; ?>                    
                <?php endif; ?>
                <li><a href="<?=BASEURL?>flow"<?=($page=='flow'?' class="active"':'')?>>Поток</a></li>
                <li><a href="<?=BASEURL?>users/profile/"<?=($page=='profile'?' class="active"':'')?>>Профиль</a></li>
                <li><a href="<?=BASEURL?>users/logout/">Выйти</a></li>
        <?php else: ?>
                <li><a href="<?=BASEURL?>users/login/"<?=($page=='user'?' class="active"':'')?>>Войти</a></li>
        <?php endif; ?>
    </ul>    
    <div id="content">
	    <?=$content?>
		<div class="clearfix"></div>
    </div>
    
</div>
<div id="footer">
<div class="left">
<small>Powered by <a href="http://andrej.in.ua">Andrej.in.ua</a><br />ICQ: 851O28</small>
</div>
<div class="right">
<small>Экстремальное программирование:<br />
20.08.2011 - 21.08.2011</small>
</div>
</div>
    
    </body></html>
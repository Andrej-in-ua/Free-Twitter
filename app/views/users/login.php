<div class="left layout_13">
<h1>Авторизация</h1>
<form action="<?=BASEURL?>users/login/" method="post">
<p><label>E-Mail:<input name="email" type="text" /></label></p>
<p><label>Пароль:<input name="password" type="password" /></label></p>
<input name="login" type="hidden" value="1" />
<div class="left"><input type="submit" value="Войти" class="button" /></div>
<!--<div class="right"><a href="<?=BASEURL?>users/forgot/" class="button">Забыли пароль?</a></div>-->
</form>
</div>

<div class="right layout_23">
<h1>Регистрация</h1>
<form action="<?=BASEURL?>users/registration/" method="post">
<p><label>
Ваше имя:<input name="name" type="text" <?=(isset($_POST['name'])?'value="'.$_POST['name'].'" ':'')?>/>
</label></p>

<p><label>
E-Mail:<input name="email" type="text" <?=(isset($_POST['email'])?'value="'.$_POST['email'].'" ':'')?>/>
</label></p>

<p><label>
Пароль:<input name="password" type="password" />
</label></p>

<p><label>
Повторите пароль:<input name="password2" type="password" />
</label></p>
<p><label>
Введите код который изображен на картинке: <img id="cimg" src="<?=BASEURL?>captcha.php?rnd=reg" /><input name="captcha" type="text" />
</label></p>

<input name="reg" type="hidden" value="1" />
<div class="right"><input type="submit" value="Продолжить" class="button" /></div>
</form>
</div>
<script language="javascript">
$("#cimg").click(function(){ $("#cimg").attr("src", "<?=BASEURL?>captcha.php?rnd=reg&rand="+Math.random()); });
</script>

<?php if ( $id == $_SESSION['user_id']): ?>
<h1>Ваш профиль</h1>
<p>Ваше имя: <strong><?=$name?></strong></p>
<p>E-Mail: <strong><?=$email?></strong></p>

<div class="left layout_12">
<h1>Ваш аватар</h1>
<form action="<?=BASEURL?>users/profile/" method="post" enctype="multipart/form-data">
<img src="<?=BASEURL?>img/profile_images/<?=(is_null($image)?'default.png':'profile_'.$id.'.'.$image)?>" />
<img src="<?=BASEURL?>img/profile_images/<?=(is_null($image)?'default_small.png':'profile_'.$id.'_small.'.$image)?>" />

<p><label>
Новый аватар:
<input name="photo" type="file" />
</label></p>

<input name="img" type="hidden" value="1" />
<div class="left"><input type="submit" value="Продолжить" class="button" /></div>
</form>

</div>

<div class="right layout_12">
<h1>Измененить пароль</h1>
<form action="<?=BASEURL?>users/profile/" method="post">
<p><label>
Текущий пароль:<input name="current" type="password" />
</label></p>
<p><label>
Новый пароль:<input name="password" type="password" />
</label></p>

<p><label>
Повторите пароль:<input name="password2" type="password" />
</label></p>

<input name="pas" type="hidden" value="1" />
<div class="right"><input type="submit" value="Продолжить" class="button" /></div>
</form>
</div>
<?php else: ?>
<h1>Профиль пользователя <?=$name?></h1>
<p>E-Mail: <strong><?=$email?></strong></p>

<?php endif; ?>
<div class="left layout_23">
<script language="javascript">var user = {id: '<?=$user->id?>', name: '<?=$user->name?>', img: '<?=(isset($user->image)?$user->image:'')?>', cur: '<?=$cur?>'};</script>
<?php if ( $cur ) : ?>
    <h1>Что происходит</h1>
    <form method="post" id="flowAdd" onkeypress="javascript:if(event.keyCode==10||(event.ctrlKey && event.keyCode==13)){flow.add();}">
    <label>
    <textarea id="msg" name="msg" rows="3" onblur="cc('msg', 'msg_max', '140')" onkeypress="cc('msg', 'msg_max', '140')" onkeyup="cc('msg', 'msg_max', '140')"></textarea>
    </label>
    <div class="left">Осталось: <strong><span id="msg_max">140</span></strong></div>
    <div class="right"><input type="submit" value="Отправить" class="button" /></div>
    </form>
    <div class="clearfix"></div>
    <h1>Ваш поток сообщений</h1>
<?php else: ?>
    	<h1>Поток сообщений пользователя <?=$user->name?></h1>
<?php endif; ?>

<div class="clearfix"></div>
<div id="flowList"></div>
<a href="JavaScript:void(0);" id="flowNextTen" class="button">Следующие 10 записей</a>
<script language="javascript">flow.nextTen();</script>
</div>



<div class="right layout_13"><h1>Подписки</h1>
<?php if ( ! $cur ) : ?>
	<?php if ( ! $follow ) : ?>
        <a href="<?=BASEURL?>flow/following/<?=$user->id?>" class="button">Читать пользователя <?=$user->name?></a>
    <?php else : ?>
        <a href="<?=BASEURL?>flow/unfollowing/<?=$user->id?>" class="button">Больше не читать <?=$user->name?></a>
    <?php endif; ?>
<?php endif; ?>
<p class="hand" id="followingList"><?=($cur?'Читаете Вы':'Пользователь читает')?>: <?=count($following)?></p>
<div id="followingList-display" style="display:none">
<?php foreach ( $following as $user ): ?>
<img class="user_small_img" src="<?=BASEURL?>img/profile_images/<?=(is_null($user->image)?'default_small.png':'profile_'.$user->id.'_small.'.$user->image)?>" /><strong><a href="<?=BASEURL?>flow/user/<?=$user->id?>"><?=$user->name?></a></strong>
<?php endforeach; ?>
</div>

<p class="hand" id="followerList"><?=($cur?'Читают Вас':'Читают пользователя')?>: <?=count($follower)?></p>
<div id="followerList-display" style="display:none">
<?php foreach ( $follower as $user ): ?>
<img class="user_small_img" src="<?=BASEURL?>img/profile_images/<?=(is_null($user->image)?'default_small.png':'profile_'.$user->id.'_small.'.$user->image)?>" /><strong><a href="<?=BASEURL?>flow/user/<?=$user->id?>"><?=$user->name?></a></strong>
<?php endforeach; ?>
</div>

</div>
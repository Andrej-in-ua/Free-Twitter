/**
 * MyTWITTER UI
 *
 * @author		Sevastyanov Andrej
 * @link		http://andrej.in.ua
 * @since		Version 1.0
 * @filesource
 */
 
/************************************************************************************
  Main action and listeners
*************************************************************************************/
$(document).ready(function() {
	$("#flowAdd").submit(function(){return flow.add();});
	$("#flowNextTen").click(function(){return flow.nextTen();});
	
	$(".delete-action").click(function(e){ return flow.del(this.id.substr(4,this.id.length));});
	
	$("#followingList").click(function(){return spoiler("#followingList-display")});
	$("#followerList").click(function(){return spoiler("#followerList-display")});

});

/************************************************************************************
  Main helpers
*************************************************************************************/
	// ------------------------------------------------ //
	//	Char counter							v 1.0	//
	// ------------------------------------------------ //
function cc(f,c,l){ f = l-$('#'+f).val().length; if(f<=0) f = "<span style='color:#f00;'>" + f + "</span>"; $('#'+c).html(f); }
	
	// ------------------------------------------------ //
	//	Url decoder								v 1.0	//
	// ------------------------------------------------ //
function urldecode(a){return decodeURIComponent((a+'').replace(/\+/g, '%20'));}

	// ------------------------------------------------ //
	//	Hide/show spoiler						v 1.0	//
	// ------------------------------------------------ //
function spoiler(a){var sp = $(a); if(sp.hasClass("open"))sp.removeClass("open").hide("slow"); else sp.show("slow").addClass("open");}

/************************************************************************************
  Main controllers
*************************************************************************************/
var flow = {
	// ------------------------------------------------ //
	//	Add message to flow						v 1.0	//
	// ------------------------------------------------ //
	add: function(){
		var msg = $("#flowAdd  textarea").val();
		if ( msg.length > 140 || msg.length < 1 )
		{
			alert('Сообщение должно быть от 1 до 140 символов');
			return false;
		}
		$.post("/flow/add/", {msg: msg}, function(data){
			if ( data.error ) {
				alert( data.error );
				return false;
			}
			flow.page++;
			var new_msg = '<div id="msg-' + data.msg_id + '" class="twitt" style="display:none"><div class="left"><a href="' + BASEURL +'flow/user/' + user.user_id +' "><img class="user_small_img" src="'+BASEURL+'img/profile_images/';
			if ( user.img != '') {
				new_msg += 'profile_' + user.id + '_small.' + user.img;
			} else {
				new_msg += 'default_small.png';
			}
			new_msg += '" /></a></div><div class="user_name"><a href="' + BASEURL +'flow/user/' + user.user_id +' ">' + user.name + '</a></div>' + data.msg + '<div class="clearfix"></div><div class="hide-meta"><div class="left">' + data.date + '</div><a href="JavaScript:​void(0)​;" class="delete-action" id="del-' + data.msg_id + '" title="Удалить"><i></i>Удалить</a></div>';
			$("#flowList").prepend(new_msg);
			$("#msg-" + data.msg_id).show("slow");
			$("#flowAdd  textarea").val('')
			$(".delete-action").click(function(e){ return flow.del(this.id.substr(4,this.id.length));});
		}, 'json');
		return false;
	},
	page: 0,
	nextTen: function(){
//		flow.page = flow.page + 10;
		flow.page = $("div.twitt").length;
		$.post("/flow/nextten/", {user_id: user.id, start: flow.page}, function(data){
			if ( data.error ) {
				alert( data.error );
				return false;
			}
			
			if ( data.flow.length < 10 ) $('#flowNextTen').hide();
			
			var new_msg = '<div id="msg-page-'+flow.page+'" style="display:none">';
			for (i=0; data.flow.length > i; i++){
				var c = data.flow[i];
				
				new_msg += '<div id="msg-' + c.id + '" class="twitt"><div class="left"><a href="' + BASEURL +'flow/user/' + c.user_id +' "><img class="user_small_img" src="'+BASEURL+'img/profile_images/';
				if ( c.image != null) {
					new_msg += 'profile_' + c.user_id + '_small.' + c.image;
				} else {
					new_msg += 'default_small.png';
				}
				new_msg += '" /></a></div> <div class="user_name"><a href="' + BASEURL +'flow/user/' + c.user_id +' ">' + c.name + '</a></div>' + c.msg + '<div class="clearfix"></div><div class="hide-meta"><div class="left">' + c.date + '</div>';
				if ( user.cur && user.id == c.user_id ) {
					new_msg += '<a href="JavaScript:​void(0)​;" class="delete-action" id="del-' + c.id + '" title="Удалить"><i></i>Удалить</a>';
				}
				new_msg += '</div></div>';
			}
			new_msg += '</div>';

			$("#flowList").append(new_msg);
			$("#msg-page-"+flow.page).show(200);
			$("#flowAdd  textarea").val('')
			$(".delete-action").click(function(e){ return flow.del(this.id.substr(4,this.id.length));});
		}, 'json');

	},
	del: function(id){
		$.post("/flow/del/", {twitt_id: id}, function(data){
			if ( data.error ) {
				alert( data.error );
				return false;
			}
			flow.page--;
			$("div#msg-" + id ).hide('slow', function(){
				$("div#msg-" + id ).remove();
				if ( $("div.twitt").length <= 5 && $('#flowNextTen').css('display') != 'none') {
					flow.nextTen();
				}
				
			});
			
		}, 'json');
	}
}
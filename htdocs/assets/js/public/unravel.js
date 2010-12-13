jQuery(document).ready(function(){
    jQuery("#flashMessage").show("normal", function() {
        $("#flashMessage").fadeTo(3000, 1).fadeOut(5000);
    });
	jQuery('.showForm').click(function() {
		jQuery('.showForm').hide(function() {
			jQuery('.uploadFormHidden').show();
		});
		return false;
	});
	jQuery('.tagArtist').click(function() {
		var id = (this.id);
		jQuery.getJSON("http://www.unravelthemusic.com/artists/tag/" + id,
		function(data){
			if (data.message == 'success')
			{
				jQuery('.tagArtist').fadeOut('fast', function() {
						jQuery('.untagArtist').fadeIn('fast');			
					});
			} else {
				jQuery('.tagArtist').after(data.error);
			}
		});
	
	});
	jQuery('.untagArtist').click(function() {
		var id = (this.id);
		jQuery.getJSON("http://www.unravelthemusic.com/artists/untag/" + id,
		function(data){
			if (data.message == 'success')
			{
				jQuery('.untagArtist').fadeOut('fast', function() {
						jQuery('.tagArtist').fadeIn('fast');			
					});
			} else {
				jQuery('.untagArtist').after(data.error);
			}
		});
	
	});
	
	jQuery('.tagSong').click(function() {
		var id = (this.id);
		jQuery.getJSON("http://www.unravelthemusic.com/songs/tag/" + id,
		function(data){
			if (data.message == 'success')
			{
				jQuery('.tagSong').fadeOut('fast', function() {
						jQuery('.untagSong').fadeIn('fast');			
					});			
			} else {
				jQuery('.tagSong').after(data.error);
			}
		});
	
	});	
	jQuery('.untagSong').click(function() {
		var id = (this.id);
		jQuery.getJSON("http://www.unravelthemusic.com/songs/untag/" + id,
		function(data){
			if (data.message == 'success')
			{
				jQuery('.untagSong').fadeOut('fast', function() {
						jQuery('.tagSong').fadeIn('fast');			
					});
			} else {
				jQuery('.untagSong').after(data.error);
			}
		});
	
	});
	
	jQuery('.tagAlbum').click(function() {
		var id = (this.id);
		jQuery.getJSON("http://www.unravelthemusic.com/albums/tag/" + id,
		function(data){
			if (data.message == 'success')
			{
				jQuery('.tagAlbum').fadeOut('fast', function() {
						jQuery('.untagAlbum').fadeIn('fast');			
					});			
			} else {
				jQuery('.tagAlbum').after(data.error);
			}
		});
	
	});	
	jQuery('.untagAlbum').click(function() {
		var id = (this.id);
		jQuery.getJSON("http://www.unravelthemusic.com/albums/untag/" + id,
		function(data){
			if (data.message == 'success')
			{
				jQuery('.untagAlbum').fadeOut('fast', function() {
						jQuery('.tagAlbum').fadeIn('fast');			
					});
			} else {
				jQuery('.untagAlbum').after(data.error);
			}
		});
	
	});

	jQuery('.voteUpMeaning').click(function() {
		var id = (this.id);
		jQuery.getJSON("http://www.unravelthemusic.com/meanings/like/" + id,
        function(data){
			if (data.message == 'success')
			{
				jQuery('#' + id + 'votes').wrap('<div class="green"></div>');
				jQuery('#' + id + 'votes').text(data.newCount);
			
			} else {
				jQuery('#' + id).before(data.error);
			}
		});
	

	});
	jQuery('.voteDownMeaning').click(function() {
		var id = (this.id);
		jQuery.getJSON("http://www.unravelthemusic.com/meanings/dislike/" + id,
        function(data){
			if (data.message == 'success')
			{
				jQuery('#' + id + 'votes').wrap('<div class="red"></div>');
				jQuery('#' + id + 'votes').text(data.newCount);
			
			} else {
				jQuery('#' + id).before(data.error);
			}
		});
	

	});
	jQuery('.editMeaning').click(function() {
		var id = (this.id);
		jQuery('#' + id).fadeOut('slow', function(){
			jQuery('.meaningEdit_' + id).fadeIn('slow');
		});
		
		jQuery('.form_' + id).ajaxForm({
				dataType:  'json', 
				success:   processEdit
		});	
		
		function processEdit(json) { 	
			if(json.result == 'fail')
			{
				jQuery('.errorEdit' + id).text(json.message);
			} else {	
				jQuery('.meaningEdit_' + id).fadeOut('slow', function(){
					jQuery('#' + id).fadeIn('slow', function() {
						jQuery('.meaning_content_body_' + id).text(json.body);
						jQuery('.meaning_title_' + id).text(json.title);
					});
				});

			}
		};			
		
	});
	
	jQuery('.meaningsTabActive, .commentsTabActive').click(function() {
		return false;
	});	
	jQuery('.commentsTabInactive').click(function() {
		e1 = jQuery(this);
		e1.removeClass("commentsTabInactive").addClass('commentsTabActive');
		e2 = jQuery('.meaningsTabActive');
		e2.removeClass("meaningsTabActive").addClass("meaningsTabInactive");
		jQuery('.meanings').slideUp(function() {
			jQuery('.comments').slideDown();
		});
		bindComments();
		return false;
	});
	var bindComments = function() { 
		jQuery('.meaningsTabInactive').click(function() {
			e1 = jQuery(this);
			e1.removeClass('meaningsTabInactive').addClass('meaningsTabActive');
			e2 = jQuery('.commentsTabActive');
			e2.removeClass('commentsTabActive').addClass('commentsTabInactive');
			jQuery('.comments').slideUp(function() {
				jQuery('.meanings').slideDown();
			});
			return false;
		});
	};
			
	var text = null;			
	jQuery('.rightSearch').focus(function() {
		text = jQuery(this).attr('value');
		jQuery(this).attr('value', '');
	});
	
	jQuery('.rightSearch').blur(function() {
		if(jQuery(this).attr('value') == '')
		{
			jQuery(this).attr('value', text);
		}
	});
	
	jQuery('.show').click(function() {
		var id = (this.id);

		jQuery('.' + id + '_replies').toggle('slideDown');
	});	
	
	jQuery('.reportUser').click(function() {
		var id = (this.id);
		jQuery.getJSON("http://www.unravelthemusic.com/users/report/" + id,
        function(data){
			if (data.result == true)
			{
				jQuery('.reportUser').text('Reported').fadeOut(1500);
			
			} else {
				jQuery('.reportUser').text('Error');
			}
		});
		return false;

	});
	jQuery('.blockUser').live("click", function(){
		var id = (this.id);
		jQuery.getJSON("http://www.unravelthemusic.com/users/block/" + id,
        function(data){
			if (data.result == true)
			{
				jQuery('.blockUser').text('Unblock');
				jQuery('.blockUser').removeClass('blockUser').addClass('unblockUser');
			} else {
				jQuery('.blockUser').text('Error');
			}
		});
		return false;

	});
	
	jQuery('.unblockUser').live("click", function(){
		var id = (this.id);
		jQuery.getJSON("http://www.unravelthemusic.com/users/unblock/" + id,
        function(data){
			if (data.result == true)
			{
				jQuery('.unblockUser').text('Block');
				jQuery('.unblockUser').removeClass('unblockUser').addClass('blockUser');
			} else {
				jQuery('.unblockUser').text('Error');
			}
		});
		return false;

	});

	jQuery('.tagUser').live("click", function(){
		var id = (this.id);
		jQuery.getJSON("http://www.unravelthemusic.com/users/tag/" + id,
        function(data){
			if (data.result == true)
			{
				jQuery('.tagUser').text('Tagged');
				jQuery('.tagUser').removeClass('tagUser').addClass('untagUser');
			} else {
				jQuery('.tagUser').text('Error');
			}
		});
		return false;

	});	
	
	jQuery('.untagUser').live("click", function(){
		var id = (this.id);
		jQuery.getJSON("http://www.unravelthemusic.com/users/untag/" + id,
        function(data){
			if (data.result == true)
			{
				jQuery('.untagUser').text('Tag');
				jQuery('.untagUser').removeClass('untagUser').addClass('tagUser');
			} else {
				jQuery('.untagUser').text('Error');
			}
		});
		return false;

	});			
		
});
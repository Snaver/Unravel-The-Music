jQuery(document).ready(function(){
    jQuery("#flashMessage").show("normal", function() {
        $("#flashMessage").fadeTo(3000, 1).fadeOut(5000);
    });
	
	jQuery('.voteUpMeaning, .voteDownMeaning, .replyMeaning, .untagAlbum, .untagSong, .untagArtist, .tagArtist, .tagSong, .tagAlbum, .addMeaningLink, .addCommentLink,' +
	'.spam, .duplicate, .tagUser, .untagUser, .blockUser, .unblockUser, .reportUser').click(function() {
		jQuery('.popupRegister').load('http://www.unravelthemusic.com/users/register_small');
		jQuery('.loginController').show();
		return false;

	});
	jQuery('.overlay, .closeOverlay').click(function() {
		jQuery('.loginController').hide();
	});
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
		
});



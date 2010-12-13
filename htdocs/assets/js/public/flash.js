jQuery.noConflict();
jQuery(document).ready(function($) {
    $("#flashMessage").show("normal",
        function()
        {
			
            $("#flashMessage").fadeTo(3000, 1).fadeOut(5000);
        }
        );
}); 
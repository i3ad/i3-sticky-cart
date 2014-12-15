/**
 * Here goes all the JS Code you need in your child theme buddy!
 */
(function($) {

	$(document).ready(function() {
		$('#wccs-content').hide(); // Hide the cart-content

		$('.wccs-handle').click(function(e) {
		  	$(this).toggleClass('open'); 
		    $('#wccs-content').slideToggle('fast');
		    e.preventDefault(); // Prevent link-jump
		});
	});

}(jQuery)); // END noConflict wrapper
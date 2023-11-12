$(function() {
	// JavaScript for handling button clicks
	document.querySelectorAll('.api-call-button').forEach(button => {
		button.addEventListener('click', function() {
			window.open(this.getAttribute('data-api-call'), '_blank');
		});
	});
});

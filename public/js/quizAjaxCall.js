$(function() {
	$('.ajax-form').on('submit', function(e) {
		e.preventDefault();

		var $form = $(this);
		var inputValue= $form.find('.ajax-data-input').val();
		var ajaxUrl = $form.data('ajax-url');

		$.ajax({
			url: ajaxUrl,
			type: 'GET',
			contentType: 'application/json',
			data: { input: inputValue },
			success: function(data) {
				$('#flashMessage').addClass('d-none').text('');

				var resultHtml = '<div class="result-item">';
				resultHtml += '<h5>Input Recognized:</h5>';
				resultHtml += '<p>' + JSON.stringify(data.inputRecognized) + '</p>';
				resultHtml += '<h5>Performance Info:</h5>';
				resultHtml += '<p>' + data.performanceInfo.replace(/\n/g, '<br>') + '</p>';
				resultHtml += '<h5>Answer:</h5>';
				resultHtml += '<p>' + data.readableStringAnswer + '</p>';
				resultHtml += '</div>';

				$form.find('.resultHistory').html(resultHtml);
			},
			error: function(error) {
				$('#flashMessage')
					.removeClass('d-none')
					.addClass('alert-danger')
					.text('Error: ' + error.responseText);
			}
		});
	});
});

$(function() {
	$('#inputForm').on('submit', function(e) {
		e.preventDefault();
		var uniquePermutations = $('#uniquePermutationsInput').val();

		$.ajax({
			url: '/api/unique-permutations',
			type: 'GET',
			contentType: 'application/json',
			data: { input: uniquePermutations },
			success: function(data) {
				$('#flashMessage').addClass('d-none').text('');

				// no html should be in js could use a template in twig or a better front end framework but job offer is backend, so it will do :)
				var resultHtml = '<div class="result-item">';
				resultHtml += '<h5>Input Recognized:</h5>';
				resultHtml += '<p>' + JSON.stringify(data.digitsRecognized) + '</p>';
				resultHtml += '<h5>Performance Info:</h5>';
				resultHtml += '<p>' + data.performanceInfo.replace(/\n/g, '<br>') + '</p>';
				resultHtml += '<h5>Permutations:</h5>';
				resultHtml += '<p>' + JSON.stringify(data.permutations) + '</p>';
				resultHtml += '</div>';

				// clear previous result and append the new result or coult show a list of x results
				$('#resultHistory').html(resultHtml);
			},
			error: function(error) {
				$('#flashMessage')
					.addClass('alert-danger')
					.text('Error: ' + error.responseText)
					.removeClass('d-none');
			}
		});
	});
});

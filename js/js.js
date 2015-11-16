$(document).ready(function(){

	$('.project a').on('mouseenter', function(){
		$(this).parent().find('.tool-tip')
			  .animate(
			    {
			    	opacity: 1,
			    	marginTop: '30px'
			    },
			    {
			    	queue: false,
			    	duration: 'fast',
			    	complete:  function() {
			    		$(this).css('z-index', '1');
			    	}
			    }).css('z-index', '1');
	});

	$('.project a').on('mouseleave', function(){
		$(this).parent().find('.tool-tip')
			  .animate(
			    {
			    	opacity: 0,
			    	marginTop: '0px'
			    },
			    {
			    	queue: false,
			    	duration: 'fast',
			    	complete:  function() {
			    		$(this).css('z-index', '-1');
			    	}
			    });

	});

	$('.login-btn').on('click', function(e){
		$('.login-overlay').fadeIn();
		$('.login-username').focus();
	});

	$('.close-login').on('click', function(e){
		$('.login-overlay').fadeOut();
	});

	$('.alert .fa-times').on('click', function(e){
		$(this).parent().fadeOut();
	});

	// Info Page

		$('.info-show-licence').on('click', function(e){
			$(this).hide();
			$('.info-licence-text').slideDown();
		});

 	// Install Page

 		$('.install-hidden-form-button').on('click', function(e){
			$('.hidden-install-form').submit();
		});


	initTimeouts();
	initManagePage();
	initSettingsPage();
});



var validateText = function(val){
	if(val.length > 0){
		return true;
	}
	else{
		return false;
	}
};


var setupDetails;
var setupCount;
var initSetup = function(){
	console.log("Initalizing Setup...");
	setupDetails = [];
	setupCount = 0;

	$('.setup-restart').on('click', function(e){
		location.reload();
	});

	$('.setup-input').on('keydown', function(e){
		if(e.keyCode == 13){
			var index = $(this).attr('data-setup');
			$('.setup-next[data-setup="' + index + '"]').click();
		}
	});

	$('.setup-btn').on('click', function(e){
		var index = parseInt($(this).attr('data-setup'));
		var $input = $('input[data-setup="' + index + '"]');
		var val = ($(this).hasClass('setup-skip')) ? $input.attr('data-default') : $input.val();

		if(validateText(val)){
			$input.removeClass('setup-input-error');
			setupDetails[index] = val;
			val = ($('.setup-result[data-setup="' + index + '"]').hasClass('setup-password')) ? '********' : val;
			$('.setup-result[data-setup="' + index + '"]').html(val);
			$('input[data-setup="' + index + '"]').hide();
			$('.setup-btn[data-setup="' + index + '"]').hide();
			$('.setup-result[data-setup="' + index + '"]').fadeIn();

			$('.show-' + (index+1)).fadeIn();
			$('.setup-input[data-setup="' + (index+1) + '"]').focus();	//focus
			setupCount++;

			if(setupCount >= 2){
				$('.setup-skip-all-section').fadeIn();

				if(setupCount === 5){
					$('.setup-skip-all').hide();
					$('.setup-finish').show();
				}
			}
		} else {
			$input.addClass('setup-input-error');
		}
	});

	$('.setup-skip-all').on('click', function(){
		for(var i = 0; i <= 4; i++){
			if(setupDetails[i]){
				continue;
			} else {
				$('.setup-skip[data-setup="' + i + '"]').click();
			}
		}
	});

	$('.setup-finish').on('click', function(){
		$('.setup-hidden.setup-details').val(JSON.stringify(setupDetails));
		$('.hidden-setup-form').submit();
	});

};



var initManagePage = function(){

	$('.add-edit-tt').on('click', function(e){
		e.stopPropagation();
		$('.tooltip-text').fadeOut();
		$(this).parent().find('.tooltip-text').fadeIn();
	});

	$('.tooltip-text').on('click', function(e){
		e.stopPropagation();
	});

	$('.close-edit-tt').on('click', function(e){
		$(this).parent().fadeOut();
	});

	$('.delete-project-btn').on('click', function(e){
		if(confirm('Are you sure you want to delete this project?')){
			$('.edit-input-method').val('delete');
			$('.manage-form').submit();
		} else {
			return;
		}
	});
};



var initSettingsPage = function(){

	$('.delete-section-link').on('click', function(e){
		if(confirm('Are you sure you want to delete this section header?')){
		} else {
			e.preventDefault();
		}
	});

};


var initTimeouts = function(){

	// Hide warning alert
	setTimeout(function(){

		$('.alert-warning').parent().fadeOut();

		// Then hide anything else
		setTimeout(function(){
			$('.alert').parent().fadeOut();
		}, 1000);
	}, 3000);

};
function progressBoton(btn) {
	btn.prop('disabled', true);
	btn.append(`<div class="spinner-border text-info" role="status">
  		<span class="visually-hidden">Loading...</span>
	</div>`);	
}

function unprogressBoton(btn) {
	setTimeout(function () {			
		btn.children('div.spinner-border').remove();
		btn.prop('disabled', false);
	}, 2000);
}

$(document).on('click', '.verPass1', function() {
	if($(this).children('i').hasClass('bx-show')){
		$(this).children('i').removeClass('bx-show');
		$(this).children('i').addClass('bx-hide');
		$(this).parent().children('input.contra').attr('type', 'text');
	}else{
		$(this).children('i').removeClass('bx-hide');
		$(this).children('i').addClass('bx-show');
		$(this).parent().children('input.contra').attr('type', 'password');
	}
});

$(document).on('click', '.verPass2', function() {
	if($(this).children('i').hasClass('fa-eye')){
		$(this).children('i').removeClass('fa-eye');
		$(this).children('i').addClass('fa-eye-slash');
		$("#"+$(this).attr('attrForm')+' input.contras').attr('type', 'text');
	}else{
		$(this).children('i').removeClass('fa-eye-slash');
		$(this).children('i').addClass('fa-eye');
		$("#"+$(this).attr('attrForm')+' input.contras').attr('type', 'password');
	}
});

$(document).on('click', '.verPass3', function() {
	if($(this).children('i').hasClass('fa-eye')){
		$(this).children('i').removeClass('fa-eye');
		$(this).children('i').addClass('fa-eye-slash');
		$("#"+$(this).attr('attrForm')+' input.contrasG').attr('type', 'text');
	}else{
		$(this).children('i').removeClass('fa-eye-slash');
		$(this).children('i').addClass('fa-eye');
		$("#"+$(this).attr('attrForm')+' input.contrasG').attr('type', 'password');
	}
});
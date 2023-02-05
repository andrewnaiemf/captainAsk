@push('js')
<script type="text/javascript">
// Loading For Submit Buttons
var loading = '<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>';

function scrollTo(IdorClass){
	var element_validate = $(IdorClass+' ');

	if(element_validate.length){
		$('body,html').animate({
	            scrollTop: element_validate.offset().top - 100
	    }, 100,'swing');
    }
}

function showSweetAlertMessage(data,redirect=''){
// redirect to index module if click add btn
 if(redirect == 'add' || redirect == 'edit'){
 	setTimeout(function(){
	 	if(redirect == 'edit'){
			window.location.href = '{{url(app('dash')['DASHBOARD_PATH'].'/resource/'.$resourceName)}}';
	 	}else{
		window.location.href = $('#form').attr('action');
	 	}
 	}, 2000);
 	}else if(redirect == 'add_show'){
 		setTimeout(function(){
			window.location.href = '{{url(app('dash')['DASHBOARD_PATH'].'/resource/'.$resourceName)}}/'+data['id'];
			}, 2000);
	}else if(redirect == 'edit_show'){
		setTimeout(function(){
			window.location.href = '{{url(app('dash')['DASHBOARD_PATH'].'/resource/'.$resourceName)}}/'+data['id'];
		}, 2000);
	}
 toastr.success(data['message']);
}

$(document).ready(function(){
 // Save action to redirect
 var btnAction;
 var backupBtnData;
 var backupBtnValue;
 $(document).on('click','button[type="submit"]',function(){
 	backupBtnData = $(this).html();
 	backupBtnValue = $(this).val();
 	btnAction = $(this).attr('name');
 });

 // Prepare Form Data And Button
 var form_id = '#form';
 var buttons = $('button[type="submit"]');
 // Start Ajax Code
	$(document).on('submit',form_id,function(e){
	 var form = $(form_id)[0];
	 $.ajax({
	    url: $(form).attr("action"),
	    type: $(form).attr("method"),
	    dataType: "JSON",
	    data: new FormData(form),
	    processData: false,
	    contentType: false,
	    beforeSend: function(){
	       buttons.prop('disabled',true);
	       $('button[value="'+backupBtnValue+'"]').html(loading+backupBtnData);
	       $('div.invalid-feedback').remove();
	       $('select,input').removeClass('is-invalid');
	       // if has translatable
		   $('.dotted').remove();
	    },success: function (data, status){
	       scrollTo(form_id);
	       $('.spinner-grow').remove();
	       buttons.prop('disabled',false);
	       $('button[value="'+backupBtnValue+'"]').html(backupBtnData);
	       if(backupBtnValue == 'add' || backupBtnValue == 'add_again' || backupBtnValue == 'add_show'){
	        form.reset();
	        $("select").val('').trigger('change');
	       }
	       showSweetAlertMessage(data,backupBtnValue);
	    },error: function (xhr, desc, err){
	       buttons.prop('disabled',false);
	       $('button[value="'+backupBtnValue+'"]').html(backupBtnData);
	       $('.spinner-grow').remove();
	       if(xhr && xhr.responseJSON && xhr.responseJSON.errors){
	        var errors = xhr.responseJSON.errors;
	        scrollTo(form_id+' .'+Object.keys(errors)[0]);
	         $.each(errors,function(key,value){

	         	// if has translatable Start
	         	var key = key.replace('.','_');
	         	$('#'+key+'-tab').append('<i class="text-danger dotted">&#8226;</i>');
	         	// if has translatable End

	            $(form_id+' #'+key).addClass('is-invalid');

	            if($(form_id+' #'+key).attr('type') == 'file'){

	            $(form_id+' .'+key).append(`<div class="invalid-feedback">`+value[0]+`</div>`);

	              $(form_id+' #'+key).parent('div').append(`<div class="invalid-feedback">`+value[0]+`</div>`);

	            }else if($(form_id+' .'+key+':has(select)')){

	            $(form_id+' .'+key).append(`<div class="invalid-feedback">`+value[0]+`</div>`);

	            $(form_id+' .'+key).parent('div').append(`<div class="invalid-feedback">`+value[0]+`</div>`);


	            }else{

	            $(form_id+' #'+key).parent('div').append(`<div class="invalid-feedback">`+value[0]+`</div>`);


	            }
	              $('.invalid-feedback').show();
	         });
	       }
	    },
	    statusCode: {
        500: function(err) {
       	  toastr.error(err?.responseJSON?.message);
        }
      }
	 });
	 // Stop Form To submition
	 e.preventDefault(e);
	});
	// End Ajax Code
});
</script>
@endpush
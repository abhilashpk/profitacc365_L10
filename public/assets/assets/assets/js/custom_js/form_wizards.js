"use strict";

$(function() {	 //$(document).ready(function () {

// bootstrap wizard//
   $('#frmEmployee').bootstrapValidator({
        fields: {
			code: { validators: { 
					notEmpty: { message: 'The employee code is required and cannot be empty!' },
					remote: { url: urlcode+'/checkcode',
							  data: function(validator) {
								return { code: validator.getFieldElements('code').val() };
							  },
							  message: 'The employee code is not available'
                    }
                }
            },
			/* code2: { validators: { 
					remote: { url: urlcode+'/checkcode',
							  data: function(validator) {
								return { code: validator.getFieldElements('code2').val(),
										id: validator.getFieldElements('id').val()};
							  },
							  message: 'The employee code is not available'
                    }
                }
            }, */
			name: { validators: { notEmpty: { message: 'The employee name is required and cannot be empty!' } } },
			
			photo: { validators: {
						file: {
							  extension: 'jpg,jpeg,png,gif',
							  type: 'image/jpg,image/jpeg,image/png,image/gif',
							  maxSize: 5*1024*1024,   // 5 MB
							  message: 'The selected file is not valid, it should be (jpg,jpeg,png,gif) and 5 MB at maximum.'
						}
					}
			}
			
          
        }
        
    }).on('reset', function (event) {
        $('#frmEmployee').data('bootstrapValidator').resetForm();
    });
	
	var emp_id=''; var frm = 1;
    $('#rootwizard').bootstrapWizard({ 
        'tabClass': 'nav nav-pills',
        'onNext': function (tab, navigation, index) { 
            var $validator = $('#frmEmployee').data('bootstrapValidator').validate();//console.log($validator.isValid());
            if($validator.isValid()) {
				if(frm==1) {
					$.ajax({
						  type: "POST",
						  url: urlcode+"/ajax_save",
						  data: $('#frmEmployee').serialize(),
						  //error: function(response) {console.log('ERROR '+Object.keys(response)); },
						  success: function(response) { //console.log(response); 
							emp_id = response;
							frm = 2;
							$('#emp_id').val(response);
							$('#frm').val(2);
							//$("#prm").removeAttr("disabled");
						  }
					});
				} else if(frm==2) {
					$.ajax({
						  type: "POST",
						  url: urlcode+"/ajax_save",
						  data: $('#frmEmployee').serialize(),
						  success: function(response) { //console.log(response); 
							frm = 3;
							$('#frm').val(3);
							$("#prm").removeAttr("disabled");
						  }
					});
				} else if(frm==3) {
					$.ajax({
						  type: "POST",
						  url: urlcode+"/ajax_save",
						  data: $('#frmEmployee').serialize(),
						  success: function(response) { //console.log(4); 
							frm = 4;
							$('#frm').val(4); 
							$('#frmEmployee').bootstrapValidator('revalidateField');
							//$('#finished').html('<button type="submit" class="btn btn-primary" id="prm">Submit1</button>');
							 $("#prm").removeAttr("disabled");
						  }
					});
				} else if(frm==4) {
					$("#prm").removeAttr("disabled");
				}
				
			} else 
				return false
			
        },
        onTabClick: function (tab, navigation, index) {
            return false;
        },
        onTabShow: function (tab, navigation, index) { 
            var $total = navigation.find('li').length;
            var $current = index + 1;
            var $percent = ($current / $total) * 100;
			
            // If it's the last tab then hide the last button and show the finish instead
            var root_wizard = $('#rootwizard');
            if ($current >= $total) { 
                root_wizard.find('.pager .next').hide();
                root_wizard.find('.pager .finish').show();
                root_wizard.find('.pager .finish').removeClass('disabled');
            } else { 
                root_wizard.find('.pager .next').show();
                root_wizard.find('.pager .finish').hide();
            }
            root_wizard.find('.finish').click(function () { 
                var $validator = $('#frmEmployee').data('bootstrapValidator').validate();
                if ($validator.isValid()) { //alert('hi');
					$.ajax({
						  type: "POST",
						  url: urlcode+"/ajax_save",
						  data: $('#frmEmployee').serialize(),
						  success: function(response) { //console.log(response); 
							location.href=returl;
						  }
					});
					
					/* document.frmEmployee.action=saveurl;
					document.frmEmployee.submit(); */
                    location.href=returl;
                    return $validator.isValid();
                    root_wizard.find("a[href='#tab1']").tab('show');
                }
            });

        }
    });
    $('#myModal').on('hide.bs.modal', function (e) {
        location.reload();
    });

    $('input[type="checkbox"].custom-checkbox, input[type="radio"].custom-radio').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue',
        increaseArea: '20%'
    });
// bootstrap wizard 2


    var navListItems = $('div.setup-panel div a'),
        allWells = $('.setup-content'),
        allNextBtn = $('.nextBtn'),
        allPrevBtn = $('.prevBtn');

    allWells.hide();

    navListItems.click(function (e) {
        e.preventDefault();
        var $target = $($(this).attr('href')),
            $item = $(this);

        if (!$item.hasClass('disabled')) {
            navListItems.removeClass('btn-primary').addClass('btn-default');
            $item.addClass('btn-primary');
            allWells.hide();
            $target.show();
            $target.find('input:eq(0)').focus();
        }
    });

    allNextBtn.click(function () {
        var curStep = $(this).closest(".setup-content"),
            curStepBtn = curStep.attr("id"),
            nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
            curInputs = curStep.find("input[type='text'],input[type='url']"),
            isValid = true;

        $(".form-group").removeClass("has-error");
        for (var i = 0; i < curInputs.length; i++) {
            if (!curInputs[i].validity.valid) {
                isValid = false;
                $(curInputs[i]).closest(".form-group").addClass("has-error");
            }
        }

        if (isValid)
            nextStepWizard.removeAttr('disabled').trigger('click');
    });

    allPrevBtn.click(function () {
        var curStep = $(this).closest(".setup-content"),
            curStepBtn = curStep.attr("id"),
            prevStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().prev().children("a");

        $(".form-group").removeClass("has-error");
        prevStepWizard.removeAttr('disabled').trigger('click');
    });

    $('div.setup-panel div a.btn-primary').trigger('click');


    $("a[disabled='disabled']").removeAttr("disabled");
});

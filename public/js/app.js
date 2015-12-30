enrollForm = function() {
	var instance = this;
	var loader = $('.loader');
	var v;
	Dropzone.autoDiscover = false;

	var IMEIdropzoneOpts = {
		url: document.location.origin + "/main/upload",
		maxFiles:1,
		maxFilesize: 5,
		paramName: 'device_sn_image',
		clickable: ['.upload-form p span'],
		acceptedFiles: "image/gif,image/jpeg,image/jpg,image/x-png,image/png,application/pdf",
		previewsContainer: "#preview-template",
		thumbnailWidth: 27,
		thumbnailHeight: 48,
		headers: { 'X-CSRF-TOKEN' : $("input[name='_token']").val() },
		maxfilesexceeded: function(file) {
			this.removeAllFiles();
			this.addFile(file);
		},
		processing: function() {
			if (this.files.length > 1) {
				this.removeFile(this.files[0]);
			}
		},
		success: function (response) {
			if(response.xhr) {
				var data = JSON.parse(response.xhr.responseText);
				$('#device_sn_upload').val(data.uploaded_path);
				if(v.checkForm()){
					$('input[type="submit"]').removeClass('disabled').attr('disabled',false);
				}
			} else {
				alert('Upload problem, please try again');
			}
		},
		init: function () {
			if($('#device_sn_upload').val()){
				var existingUrl = $('#device_sn_upload').val();
				var existingName = existingUrl.match(/.*\/(.*)$/)[1];
				var existingFile = { name: existingName, size: 12345, url: existingUrl };
				this.options.addedfile.call(this, existingFile);
				this.options.thumbnail.call(this, existingFile, existingFile.url);
				this.files = [existingFile];
			}
			this.on("error", function(file,error){
				if (!Dropzone.isValidFile(file,this.options.acceptedFiles)){
					this.removeFile(file);
					alert(error);
				}
			});
		}
	}
	var receiptdropzoneOpts = {
		url: document.location.origin + "/main/upload",
		maxFiles:1,
		maxFilesize: 5,
		paramName: 'receipt_image',
		clickable: ['.upload-form p span'],
		acceptedFiles: "image/gif,image/jpeg,image/jpg,image/x-png,image/png,application/pdf",
		previewsContainer: "#preview-template2",
		thumbnailWidth: 27,
		thumbnailHeight: 48,
		headers: { 'X-CSRF-TOKEN' : $("input[name='_token']").val() },
		maxfilesexceeded: function(file) {
			this.removeAllFiles();
			this.addFile(file);
		},
		processing: function() {
			if (this.files.length > 1) {
				this.removeFile(this.files[0]);
			}
		},
		success: function (response) {
			if(response.xhr) {
				var data = JSON.parse(response.xhr.responseText);
				$('#receipt_upload').val(data.uploaded_path);
				if(v.checkForm()){
					$('input[type="submit"]').removeClass('disabled').attr('disabled',false);
				}
			}
		},
		init: function () {
			if($('#receipt_upload').val()){
				var existingUrl = $('#receipt_upload').val();
				var existingName = existingUrl.match(/.*\/(.*)$/)[1];
				var existingFile = { name: existingName, size: 12345, url: existingUrl };
				this.options.addedfile.call(this, existingFile);
				this.options.thumbnail.call(this, existingFile, existingFile.url);
				this.files = [existingFile];
			}
			this.on("error", function(file,error){
				if (!Dropzone.isValidFile(file,this.options.acceptedFiles)){
					this.removeFile(file);
					alert(error);
				}
			});
		}
	}

	function initialize() {
		if($('#device_sn_image').length != 0){
			var myDropZone = new Dropzone("#device_sn_image",IMEIdropzoneOpts);
		}
		if($('#receipt_image').length != 0){
			var myDropZone2 = new Dropzone("#receipt_image",receiptdropzoneOpts);
		}

		$(document).ready(function(){
			instance.formValidator();

			if(v){
				if(!v.checkForm()){
					$('input[type="submit"]').addClass('disabled').attr('disabled',true);
				} else {
					$('input[type="submit"]').removeClass('disabled').attr('disabled',false);
				}
			}

			$(':input[type=number]').on('mousewheel', function(e){ e.preventDefault(); });

			$('.checkbox-container span').on("click", function(){
				var checkbox = $(this).parent('.checkbox-container').find(':checkbox');
				checkbox.prop("checked", !checkbox.prop("checked"));
				if (v.checkForm()) {
					$('input[type="submit"]').removeClass('disabled').attr('disabled',false);
				} else {
					$('input[type="submit"]').addClass('disabled').attr('disabled',true);
				}
			});
		});
	}

	this.formValidator = function(){

		// form validation
		$("#purchased_at").mask("00r00r0000", {
			translation: {
				'r': {
					pattern: /[\/]/,
					fallback: '/'
				},
				placeholder: "__/__/____"
			}
		});
		$("#postcode").mask("00000");
		$('#device_imei').mask("00 000000 000000 0");

		jQuery.validator.addMethod("dateRange",function(value,element) {
			var today = new Date();
			var parts = value.split("/");
			var date = new Date(parts[0] + "/" + parts[1] + "/" + parts[2]);
			return Math.round(Math.abs(today - date) / (1000 * 60 * 60 * 24)) <= 14;
		}, jQuery.validator.format("You need to register within 14 days of purchase"));
		jQuery.validator.addMethod("phoneUS",function(a,b){return a=a.replace(/\s+/g,""),this.optional(b)||a.length>9&&a.match(/^(\+?1-?)?(\([2-9]([02-9]\d|1[02-9])\)|[2-9]([02-9]\d|1[02-9]))-?[2-9]([02-9]\d|1[02-9])-?\d{4}$/)},"Please specify a valid phone number")

		v = $(".enroll-form").validate({
			ignore: [],
			onkeyup: function( element, event ) {
				if (v.checkForm()) {
					$('input[type="submit"]').removeClass('disabled').attr('disabled',false);
					$(element).valid();
				} else {
					$('input[type="submit"]').addClass('disabled').attr('disabled',true);
				}
			},
			onfocusout: function( element ) {
				if (v.checkForm()) {
					$('input[type="submit"]').removeClass('disabled').attr('disabled',false);
					$(element).valid();
				} else {
					$('input[type="submit"]').addClass('disabled').attr('disabled',true);
					$(element).valid();
				}

			},
			onclick: function( element ) {
				if (v.checkForm()) {
					$('input[type="submit"]').removeClass('disabled').attr('disabled',false);
					$(element).valid();
				} else {
					$('input[type="submit"]').addClass('disabled').attr('disabled',true);
					$(element).valid();
				}
			},
			rules: {
				email: 'required',
				emailConfirm: {
					equalTo: '#email'
				},
				purchased_at: {
					required: true,
					dateRange: true
				},
				device_imei: {
					required: true,
					rangelength: [15, 18]
				},
				case_sn: {
					required: true
				},
				device_sn: {
					required: true
				},
				reg_code: {
					required: true,
					rangelength: [10, 10],
					digits: true
				},
				device_sn_upload: {
					required: true
				},
				postcode: {
					required: true,
					rangelength: [5, 5]
				},
				phone: {
					required: true,
					phoneUS: true
				}
			},
			errorPlacement: function(error, element) {
				if(element.attr('id') == 'purchased_at' && error.text() != "This field is required."){
					error.prependTo(element.parents('form'));
				}
			}
		});
	}
	initialize();
}

claimForm = function() {
	var instance = this;
	var loader = $('.loader');
	var v2;

	function initialize() {

		$(document).ready(function(){
			instance.formValidator();

			if(v2){
				if(!v2.checkForm()){
					$('input[type="submit"]').addClass('disabled').attr('disabled',true);
				} else {
					$('input[type="submit"]').removeClass('disabled').attr('disabled',false);
				}
			}

			$(':input[type=number]').on('mousewheel', function(e){ e.preventDefault(); });

			$('.checkbox-container span').on("click", function(){
				var checkbox = $(this).parent('.checkbox-container').find(':checkbox');
				checkbox.prop("checked", !checkbox.prop("checked"));
				instance.showbillingAddress(checkbox.prop("checked"));
				if (v2.checkForm()) {
					$('input[type="submit"]').removeClass('disabled').attr('disabled',false);
				} else {
					$('input[type="submit"]').addClass('disabled').attr('disabled',true);
				}
			});

			// Checkout form
			// Same as shipping information toggler
			if ($('#edit-type').val() == 'non-edit') {
				$('#billing-information').hide();
			}

			$('#same_as_shipping:checkbox').change( function(){
				instance.showbillingAddress(this.checked);
			});

		});
	}

	this.showbillingAddress = function(checker) {
		if (checker) {
			$('#billing-information').slideUp("slow", function(){
				$('.billing-fields').each(function(i){
					field = $(this);
					fieldId = field.attr('id').split("_");
					oldVal = $('#shipping_' + fieldId[1]).val();
					field.val(oldVal);
				});
			});
		} else {
			$('.billing-fields').val('');
			$('#billing-information').slideDown("slow");
		}
	}

	this.formValidator = function(){

		// form validation
		$("#postcode").mask("00000");
		$("#cc_cid").mask("000Z", {
			translation: {
				'Z': {
					pattern: /[0-9]/, optional: true
				}
			}
		});

		v2 = $(".claim-form").validate({
			ignore: [],
			onkeyup: function( element, event ) {
				if (v2.checkForm()) {
					$('input[type="submit"]').removeClass('disabled').attr('disabled',false);
					$(element).valid();
				} else {
					$('input[type="submit"]').addClass('disabled').attr('disabled',true);
				}
			},
			onfocusout: function( element ) {
				if (v2.checkForm()) {
					$('input[type="submit"]').removeClass('disabled').attr('disabled',false);
					$(element).valid();
				} else {
					$('input[type="submit"]').addClass('disabled').attr('disabled',true);
					$(element).valid();
				}

			},
			onclick: function( element ) {
				if (v2.checkForm()) {
					$('input[type="submit"]').removeClass('disabled').attr('disabled',false);
					$(element).valid();
				} else {
					$('input[type="submit"]').addClass('disabled').attr('disabled',true);
					$(element).valid();
				}
			},
			rules: {
				shipping_postcode: {
					required: true,
					rangelength: [5, 5]
				},
				billing_postcode: {
					required: true,
					rangelength: [5, 5]
				},
				cc_cid: {
					required: true,
					rangelength: [3,4],
					digits: true
				},
				cc_num: {
					required: true,
					digits: true
				},
				billing_email: 'required',
				shipping_email: 'required'
			},
			errorPlacement: function(error, element) {
				if(element.attr('id') == 'purchased_at' && error.text() != "This field is required."){
					error.prependTo(element.parents('form'));
				}
			}
		});
	}
	initialize();
}

function pageNav() {
	var instance = this;
	var loader = $('.loader');

	function initialize() {
		$("body").on({ click: instance.newpage }, ".sitenav a");
		$("body").on({ click: instance.newpage }, "#modal-continue");
		if(loader.length > 0){ instance.buildLoader(); }
	}
	this.newpage = function(e) {
		e.preventDefault();

		var el = $(e.target).closest('a');
		newLocation = el.attr("href");

		$('#watertestConfirm').modal('hide');

		if($("body").hasClass('home')) {
			instance.homeani(newLocation);
		} else {
			instance.pageani(newLocation);
		}

	};
	// handles animation for navigation from home page
	this.homeani = function(newLocation) {

		$('.header').css('opacity','0' );
		$('.content').css('opacity','0' );
		instance.showLoader();

		$('.sitenav').addClass('moveup');
		$('.sitenav').animate({
			top: "0"
		}, 1000, function() {
			if(newLocation != undefined){
				window.location = newLocation;
			}
		});
	};
	// handles animation from other pages
	this.pageani = function(newLocation) {

		if(newLocation != undefined){
			$('.header').css('opacity','0' );
			$('.content').css('opacity','0' );
			instance.showLoader();

			// we want to warn the user if they have started filling out the form before taking them away and losing it all
			if($('body').hasClass('enroll')){
				//check if on first step or form has already been submitted
				if($('body').hasClass('thankyou') || $('body').hasClass('checkcode')){
					window.setTimeout(function() {
						window.location = newLocation;
					}, 250);
				} else {
					// prompt for confirmation
					if(confirm('Leaving now will lose all of the form data. Continue?')){
						window.location = newLocation;
					} else {
						instance.hideLoader();
						$('.header').css('opacity','1' );
						$('.content').css('opacity','1' );
					}
				}
			} else { // any other pages
				window.setTimeout(function() {
					window.location = newLocation;
				}, 250);
			}
		}
	};
	this.buildLoader = function(){
		for (var i = 0; i < 5; i++) {
			if(i == 4) {
				var loaderBar = "<div class='square last'></div>";
			} else {
				var loaderBar = "<div class='square'></div>";
			}
			loader.append(loaderBar);
		};
	};
	this.showLoader = function(){
		loader.addClass('show');
	}
	this.hideLoader = function(){
		loader.removeClass('show');
	}
	initialize();
}

$(document).ready(function() {
	var mophie_nav = new pageNav();

	if ($('.enroll-form').length > 0){
		new enrollForm();
	}

	if ($('.claim-form').length > 0){
		new claimForm();
	}

});
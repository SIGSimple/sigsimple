$(document).ready(function() {

	// Headhesive
	var options = {
	    offset: 600,
	    classes: {
	        clone:   'banner--clone',
	        stick:   'banner--stick',
	        unstick: 'banner--unstick'
	    }
	};

	// Initialise with options
	var banner = new Headhesive('.navbar', options);

	// Tabs
	$( "#tabs, #horz_tabs" ).tabs();

	// Accordion
	$( "#accordion" ).accordion();
	  
	// Page loading animation
	setTimeout(function() {
        $('body').addClass('loaded');
    }, 500);

    // Wrap body content
    $("body").wrapInner( "<div class='wrapper'></div>");

    // Equal height pricing
    $('.price, .response > div').matchHeight();

    // Set button width inside pricing tables
    var width = $('.price').width();
	$('.price .btn').width(width - 60);

    $(window).resize(function() {

	    var width = $('.price').width();
		$('.price .btn').width(width - 60);

	});

	// Replace logo
	$(".banner--clone .navbar-header a").replaceWith('<img src="images/logo.png" alt="Evoke web app landing page">');

	// Animated dropdown menu
	$(".nav").bootstrapDropdownOnHover({
		mouseOutDelay: 100, // Number of milliseconds to wait before closing the menu on mouseleave
		responsiveThreshold: 992, // Pixel width where the menus should no-longer be activated by hover
	});

	// Google maps
	if ($("#map").length > 0){
		$('#map').gmap3({
			map: {
			    options:{
			        zoom:16,
			        center: [51.731805, 0.671448],
			        mapTypeId: google.maps.MapTypeId.MAP,
			        mapTypeControl: false,
			        mapTypeControlOptions: {
           mapTypeIds: [google.maps.MapTypeId.ROADMAP, "style1"]
        },
			        navigationControl: true,
			        scrollwheel: false,
			        streetViewControl: true,
			        disableDefaultUI: false
			    },
			    styledmaptype:{
			      id: "style1",
			      options:{
			        name: "Style 1"
			      },
			      styles: [
			        {
			          featureType: "all",
			          elementType: "all",
			          stylers: [
			            { saturation: -100 },
			          ]
			        }
			      ]
			    }
			},
			marker:{
			    latLng: [51.731805, 0.671448],
			    options: {
				    icon: new google.maps.MarkerImage(
				        "../images/pin.png", new google.maps.Size(35, 48, "px", "px")
				    )
			    }
			 }
			}

		);
	}

	$('#btnEnviarContato').on('click', function(){		
		$.ajax({
			url: 'util/script-envia-email.php',
			type: 'POST',
			data: {
				nome: 		$('#nome').val(),
				email: 		$('#email').val(),
				telefone: 	$('#telefone').val(),
				message: 	$('#message').val()
			},
			cache: false,
			beforeSend: function(){
				$('#form_contato .form-group').removeClass('has-error');
				$('#form_contato .label').addClass('hide');
				$('#form_contato .alert').addClass('hide');
			},
			success: function(data, config, status) {
				$('#btnEnviarContato').addClass('hide');
				$('#form_contato .alert-success').text(data).removeClass('hide');
			},
			error: function(error, config, status) {
				if(error.status === 406) {
					$.each(error.responseJSON, function(field, error_message) {
						$('#'+field).closest('.form-group').addClass('has-error');
						$('#form_contato .label.'+field).text(error_message).removeClass('hide');
					});
				}
				else {
					$('#form_contato .alert-danger').text(error.responseText).removeClass('hide');
				}
			}
		});
	});

	$('#btnInscrevaNewsletter').on('click', function(){		
		$.ajax({
			url: 'util/script-envia-news.php',
			type: 'POST',
			data: {
				nome: 		$('#nome-news').val(),
				email: 		$('#email-news').val()
			},
			cache: false,
			beforeSend: function(){
				$('#form_news .form-group').removeClass('has-error');
				$('#form_news .label').addClass('hide');
				$('.alert-news').addClass('hide');
			},
			success: function(data, config, status) {
				$('#btnInscrevaNewsletter').closest("form").addClass('hide');
				$('.alert-news.alert-success').text(data).removeClass('hide');
			},
			error: function(error, config, status) {
				if(error.status === 406) {
					$.each(error.responseJSON, function(field, error_message) {
						$('#'+field).closest('.form-group').addClass('has-error');
						$('#form_news .label.'+field).text(error_message).removeClass('hide');
					});
				}
				else {
					$('.alert-news.alert-danger').text(error.responseText).removeClass('hide');
				}
			}
		});
	});
});
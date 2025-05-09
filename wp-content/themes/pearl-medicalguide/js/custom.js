$ = jQuery;
$(document).ready(function () {

    "use strict";

    /**
     * Mobile Menu
     */
    $("#menu").mmenu({
        "classes": "mm-slide",
        "offCanvas": {
            "position": "right"
        },
        "footer": {
            "add": true,
            "title": menuCustom.mfooter
        },

        "header": {
            "title": menuCustom.mheader,
            "add": true,
            "update": true
        },
    });
});

/**
 * Sticky Header
 */
$(window).scroll(function () {
    if ($(this).scrollTop() > 1) {
        $('#stikcy-header').addClass("sticky");
    }
    else {
        $('#stikcy-header').removeClass("sticky");
    }
});

/**
 * Time Table
 */
$(function () {

    var Accordion = function (el, multiple) {
        this.el = el || {};
        this.multiple = multiple || false;

        var links = this.el.find('.link');
        links.on('click', {el: this.el, multiple: this.multiple}, this.dropdown)
    }

    Accordion.prototype.dropdown = function (e) {
        var $el = e.data.el;
        $this = $(this),
            $prev = $this.prev();

        $prev.slideToggle();
        $this.parent().toggleClass('open');

        if (!e.data.multiple) {
            $el.find('.submenu').not($prev).slideUp().parent().removeClass('open');
        }
    }

    var accordion = new Accordion($('#accordion2'), false);
});

/**
 * Make an Appointment Accordion
 * @param el
 * @param multiple
 * @constructor
 */
var Accordion = function (el, multiple) {
    this.el = el || {};
    this.multiple = multiple || false;

    // Variables privadas
    var links = this.el.find('.link');
    // Evento
    links.on('click', {el: this.el, multiple: this.multiple}, this.dropdown)
}

Accordion.prototype.dropdown = function (e) {
    var $el = e.data.el;
    $this = $(this),
        $next = $this.next();

    $next.slideToggle();
    $this.parent().toggleClass('open');

    if (!e.data.multiple) {
        $el.find('.bgcolor-3').not($next).slideUp().parent().removeClass('open');
    }
    ;
}

var accordion = new Accordion($('#accordion'), false);

/**
 * Why Choose Accordion
 */
$(function () {
    var Accordion = function (el, multiple) {
        this.el = el || {};
        this.multiple = multiple || false;

// Variables privadas
        var links = this.el.find('.link');
// Evento
        links.on('click', {el: this.el, multiple: this.multiple}, this.dropdown)
    }

    Accordion.prototype.dropdown = function (e) {
        var $el = e.data.el;
        $this = $(this),
            $next = $this.next();

        $next.slideToggle();
        $this.parent().toggleClass('open');

        if (!e.data.multiple) {
            $el.find('.submenu-active').not($next).slideUp().parent().removeClass('open');
            $el.find('.submenu').not($next).slideUp().parent().removeClass('open');

        }
        ;
    }

    var accordion = new Accordion($('#pearl-accordion'), false);
});

<!-- Date Picker and input hover -->
// trim polyfill : https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/Trim

[].slice.call(document.querySelectorAll('input.input__field')).forEach(function (inputEl) {
// in case the input is already filled..

// events:
    inputEl.addEventListener('focus', onInputFocus);
    inputEl.addEventListener('blur', onInputBlur);
});

function onInputFocus(ev) {
    classie.add(ev.target.parentNode, 'input--filled');
}

function onInputBlur(ev) {
    if (ev.target.value.trim() === '') {
        classie.remove(ev.target.parentNode, 'input--filled');
    }
}

//date picker
$("#datepicker").datepicker({
    inline: true
});


[].slice.call(document.querySelectorAll('textarea.input__field')).forEach(function (inputEl) {
// in case the input is already filled..
    if (inputEl.value.trim() !== '') {
        classie.add(inputEl.parentNode, 'input--filled');
    }

// events:
    inputEl.addEventListener('focus', onInputFocus);
    inputEl.addEventListener('blur', onInputBlur);
});


//date picker
$("#datepicker").datepicker({
    inline: true
});

/**
 * Services tabs
 */
var tabbedNav = $("#tabbed-nav").zozoTabs({
    orientation: "horizontal",
    theme: "silver",
    position: "top-left",
    size: "medium",
    animation: {
        duration: 600,
        easing: "easeOutQuint",
        effects: "fade"
    },
    defaultTab: "tab1"
});


/* Changing animation effects*/
$("#config input.effects").change(function () {
    var effects = $('input[type=radio]:checked').attr("id");
    tabbedNav.data("zozoTabs").setOptions({"animation": {"effects": effects}});
});


<!-- All Carousel -->
<!-- Home News-Posts Carousel -->
$("#owl-demo").owlCarousel({
    items: 3,
    lazyLoad: true,
    navigation: true
});

/* Pie Chart */
$('#pie-charts').waypoint(function (direction) {
    $('.chart').easyPieChart({
        easing: 'easeOutBounce',
        onStep: function (from, to, percent) {
            $(this.el).find('.percent').text(Math.round(percent));
        }
    });
}, {
    offset: function () {
        return $.waypoints('viewportHeight') - $(this).height() + 100;
    }
});


<!-- Testimonials Carousel -->
$("#owl-demo2").owlCarousel({
    autoPlay: 111110,
    stopOnHover: true,

    paginationSpeed: 1000,
    goToFirstSpeed: 2000,
    singleItem: true,
    autoHeight: true,

});


$("#owl-demo4").owlCarousel({
    items: 3,
    lazyLoad: true,
    navigation: true
});


<!-- Team Detail -->
$("#team-detail").owlCarousel({

    navigation: true,
    slideSpeed: 300,
    paginationSpeed: 400,
    singleItem: true

// "singleItem:true" is a shortcut for:
// items : 1, 
// itemsDesktop : false,
// itemsDesktopSmall : false,
// itemsTablet: false,
// itemsMobile : false

});


<!-- Home2 services slide Carousel -->
$(".services-slide").owlCarousel({

    navigation: true,
    slideSpeed: 300,
    paginationSpeed: 400,
    singleItem: true

// "singleItem:true" is a shortcut for:
// items : 1, 
// itemsDesktop : false,
// itemsDesktopSmall : false,
// itemsTablet: false,
// itemsMobile : false

});


<!-- Blog images slide Carousel -->
$("#blog-slide").owlCarousel({

    navigation: true,
    slideSpeed: 300,
    paginationSpeed: 400,
    singleItem: true

// "singleItem:true" is a shortcut for:
// items : 1, 
// itemsDesktop : false,
// itemsDesktopSmall : false,
// itemsTablet: false,
// itemsMobile : false

});


<!-- Back to Top -->
jQuery(document).ready(function ($) {
// browser window scroll (in pixels) after which the "back to top" link is shown
    var offset = 300,
//browser window scroll (in pixels) after which the "back to top" link opacity is reduced
        offset_opacity = 1200,
//duration of the top scrolling animation (in ms)
        scroll_top_duration = 1400,
//grab the "back to top" link
        $back_to_top = $('.cd-top');

//hide or show the "back to top" link
    $(window).scroll(function () {
        ( $(this).scrollTop() > offset ) ? $back_to_top.addClass('cd-is-visible') : $back_to_top.removeClass('cd-is-visible cd-fade-out');
        if ($(this).scrollTop() > offset_opacity) {
            $back_to_top.addClass('cd-fade-out');
        }
    });

//smooth scroll to top
    $back_to_top.on('click', function (event) {
        event.preventDefault();
        $('body,html').animate({
                scrollTop: 0,
            }, scroll_top_duration
        );
    });

});


//Procedures Links
var Accordion = function (el, multiple) {
    this.el = el || {};
    this.multiple = multiple || false;

// Variables privadas
    var links = this.el.find('.link');
// Evento
    links.on('click', {el: this.el, multiple: this.multiple}, this.dropdown)
}

Accordion.prototype.dropdown = function (e) {
    var $el = e.data.el;
    $this = $(this),
        $next = $this.next();

    $next.slideToggle();
    $this.parent().toggleClass('open');

    if (!e.data.multiple) {
        $el.find('.submenu').not($next).slideUp().parent().removeClass('open');
    }
    ;
}

var accordion = new Accordion($('#procedures-links'), false);


//Procedures FAQ'S
var Accordion = function (el, multiple) {
    this.el = el || {};
    this.multiple = multiple || false;

// Variables privadas
    var links = this.el.find('.link');
// Evento
    links.on('click', {el: this.el, multiple: this.multiple}, this.dropdown)
}

Accordion.prototype.dropdown = function (e) {
    var $el = e.data.el;
    $this = $(this),
        $next = $this.next();

    $next.slideToggle();
    $this.parent().toggleClass('open');

    if (!e.data.multiple) {
        $el.find('.submenu').not($next).slideUp().parent().removeClass('open');
    }
    ;
}

var accordion = new Accordion($('#procedures-faq'), false);


//PreLoader
jQuery(window).load(function () { // makes sure the whole site is loaded
    jQuery('#status').fadeOut(); // will first fade out the loading animation
    jQuery('#preloader').delay(350).fadeOut('slow'); // will fade out the white DIV that covers the website.
    jQuery('body').delay(350).css({'overflow': 'visible'});
})


/*
 *  Fancybox for the gallery images and videos
 */
if (jQuery().fancybox) {

    $('.fancybox').fancybox();

    $(document).ready(function () {
        $('.fancybox-media').fancybox({
            openEffect: 'none',
            closeEffect: 'none',
            helpers: {
                media: {}
            }
        });
    });
}


// Appointment newsletter contact Form	
function checkcontact(input) {
    var pattern1 = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
    if (pattern1.test(input)) {
        return true;
    }
    else {
        return false;
    }
}

function validateAppointment() {
//alert('hi');
    var errors = "";

    var app_name = document.getElementById("input-29");
    var app_email_address = document.getElementById("input-30");
    var app_date = document.getElementById("datepicker");

    if (app_name.value == "") {
        errors += 'Please provide your name.';
    }
    else if (app_email_address.value == "") {
        errors += 'Please provide an email address.';
    }
    else if (checkcontact(app_email_address.value) == false) {
        errors += 'Please provide a valid email address.';
    }
    else if (app_date.value == "") {
        errors += 'Please select an appointment date.';
    }


    if (errors) {
        document.getElementById("error").style.display = "block";
        document.getElementById("error").innerHTML = errors;
        return false;
    }
}


/*----------------------------------------------------------------------------------*/
/* Contact and Appointments forms AJAX validation and submission
 /* Validation Plugin : http://bassistance.de/jquery-plugins/jquery-plugin-validation/
 /* Form Ajax Plugin : http://www.malsup.com/jquery/form/
 /*---------------------------------------------------------------------------------- */

if (jQuery().validate && jQuery().ajaxSubmit) {

    var submitButton = $('#submit-button'),
        ajaxLoader = $('#ajax-loader'),
        messageContainer = $('#success'),
        errorContainer = $("#error");


    var formOptions = {
        beforeSubmit: function () {
            submitButton.attr('disabled', 'disabled');
            ajaxLoader.fadeIn('fast');
            messageContainer.fadeOut('fast');
            errorContainer.fadeOut('fast');
            console.log(ajaxLoader);
        },
        success: function (ajax_response, statusText, xhr, $form) {
            var response = $.parseJSON(ajax_response);
            console.log(response);
            ajaxLoader.fadeOut('fast');
            submitButton.removeAttr('disabled');
            if (response.success) {
                $form.resetForm();
                messageContainer.html(response.message).fadeIn('fast');
            } else {
                errorContainer.html(response.message).fadeIn('fast');
            }
        }
    };

    $('#contact_form, #appointment_form').each(function () {
        $(this).validate({
            errorLabelContainer: errorContainer,
            submitHandler: function (form) {
                $(form).ajaxSubmit(formOptions);
            }
        });
    });

}
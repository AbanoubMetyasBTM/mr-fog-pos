const mix = require('laravel-mix');


mix.babel([

    'public/jscode/call_at_load.js',
    'public/jscode/config.js',
    'public/jscode/tabsBroadcast.js',

    'public/front/js/jquery-ui.js',
    'public/front/js/popper.min.js',
    'public/front/js/bootstrap.min.js',
    'public/front/js/bootstrap-select.min.js',
    'public/front/js/moment.min.js',
    'public/front/js/daterangepicker.js',
    'public/front/js/owl.carousel.min.js',
    'public/front/js/jquery.fancybox.min.js',
    'public/front/js/jquery.countTo.min.js',
    'public/front/js/select2.full.min.js',
    'public/btm-select-2/btm-select-2.js',

    'public/front/js/animated-headline.js',
    'public/front/js/jquery.ripples-min.js',
    'public/front/js/quantity-input.js',
], "public/min/combine.js").version();

mix.babel([
    'public/datatables/js/jquery.dataTables.min.js',
    'public/datatables/js/dataTables.buttons.min.js',
    'public/datatables/js/jszip.min.js',
    'public/datatables/js/pdfmake.min.js',
    'public/datatables/js/vfs_fonts.js',
    'public/datatables/js/buttons.html5.min.js',
    'public/datatables/js/buttons.colVis.min.js',
], "public/min/datatable_min.js").version();


mix.babel([
    'public/jscode/front/hotels/search_form.js',
    'public/jscode/front/flights/search_form.js',
    'public/sweet_alert/sweetalert2.min.js',
    'public/jscode/routes.js',
    'public/jscode/datatable.js',
    'public/jscode/site_content.js',
    'public/jscode/firebase_notifications.js',
    'public/jscode/front/offlinePackages/offline_packages.js',
], "public/min/combine_2.js").version();


mix.styles([
    'public/front/css/bootstrap.min.css',
    'public/front/css/bootstrap-select.min.css',
    'public/front/css/line-awesome.css',
    'public/front/css/owl.carousel.min.css',
    'public/front/css/owl.theme.default.min.css',
    'public/front/css/jquery.fancybox.min.css',
    'public/front/css/daterangepicker.css',
    'public/front/css/animate.min.css',
    'public/front/css/animated-headline.css',
    'public/front/css/jquery-ui.css',
    'public/front/css/flag-icon.min.css',
    'public/front/css/style.css',
    'public/front/css/select2.min.css',
    'public/btm-select-2/btm-select-2.css',
    'public/front/css/custom.css',
    'public/front/css/custom_flights.css',
    'public/front/css/img_field.css',
    'public/sweet_alert/sweetalert2.min.css',
    'public/datatables/css/jquery.dataTables.min.css',
    'public/datatables/css/buttons.dataTables.min.css',
], 'public/front/css/css_en.css');

mix.styles([

    'public/front/css/bootstrap.min.css',
    'public/front/css/bootstrap-rtl.min.css',
    'public/front/css/bootstrap-select.min.css',
    'public/front/css/line-awesome.css',
    'public/front/css/owl.carousel.min.css',
    'public/front/css/owl.theme.default.min.css',
    'public/front/css/jquery.fancybox.min.css',
    'public/front/css/daterangepicker.css',
    'public/front/css/animate.min.css',
    'public/front/css/animated-headline.css',
    'public/front/css/jquery-ui.css',
    'public/front/css/flag-icon.min.css',
    'public/front/css/style.css',
    'public/front/css/style-rtl.css',
    'public/front/css/select2.min.css',
    'public/btm-select-2/btm-select-2.css',
    'public/front/css/custom.css',
    'public/front/css/custom_flights.css',
    'public/front/css/img_field.css',
    'public/front/css/custom-rtl.css',
    'public/sweet_alert/sweetalert2.min.css',
    'public/datatables/css/jquery.dataTables.min.css',
    'public/datatables/css/buttons.dataTables.min.css',

], 'public/front/css/css_ar.css');

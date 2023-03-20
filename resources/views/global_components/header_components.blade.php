<!-- vendor css -->
{{--<link href="{{url("/")}}/public/admin/lib/font-awesome/css/font-awesome.css" rel="stylesheet">--}}
<link href="{{url("/")}}/public/fontawesome/css/all.css" rel="stylesheet">

<link href="{{url("/")}}/public/admin/lib/Ionicons/css/ionicons.css" rel="stylesheet">

<!-- Custom general CSS -->
<link rel="stylesheet" href="{{url("/")}}/public/admin/lib/jquery-toggles/css/toggles-full.css">



<!-- Toastr -->
<link rel="stylesheet" href="{{url("/")}}/public/toastr/toastr.css">
<link href="{{url("/")}}/public/admin/lib/datatables/css/jquery.dataTables.css" rel="stylesheet">
<link href="{{url("/")}}/public/admin/lib/select2/css/select2.min.css" rel="stylesheet">
<link href="{{url("/")}}/public/btm-select-2/btm-select-2.css" rel="stylesheet">

<link href="{{url("/")}}/public/admin/lib/jquery.steps/css/jquery.steps.css" rel="stylesheet">

<link href="{{url("/")}}/public/admin/lib/bootstrap-datetimepicker/bootstrap-datetimepicker.css" rel="stylesheet">
<link href="{{url("/")}}/public/admin/lib/summernote/css/summernote-bs4.css" rel="stylesheet">
<link href="{{url("/")}}/public/admin/lib/SpinKit/css/spinkit.css" rel="stylesheet">

@include('global_components.datatable.styles')

<!-- other includes -->
@yield('additional_css')


<!-- Fonts CSS -->
<link rel="stylesheet" href="{{url("/")}}/public/admin/css/slim_fonts.css">

<!-- Slim CSS -->
<link rel="stylesheet" href="{{url("/")}}/public/admin/css/slim.css">

<!-- active below to enable dark mode -->
<?php if(config('dark_mode') == "on"): ?>
<link rel="stylesheet" href="{{url("/")}}/public/admin/css/slim.one.css">
<?php endif; ?>

<link rel="stylesheet" href="{{url("/")}}/public/admin/lib/jquery-ui/css/jquery-ui.css">

<link rel="stylesheet" href="{{url("/")}}/public/sweet_alert/sweetalert2.min.css">

<!-- Custom general CSS -->
<link rel="stylesheet" href="{{url("/")}}/public/admin/css/custom.css">
<link rel="stylesheet" href="{{url("/")}}/public/admin/css/gift_card_templates.css">

<script src="{{url("/")}}/public/admin/lib/jquery/js/jquery.js"></script>
<script src="{{url("/")}}/public/jscode/call_at_load.js" type="text/javascript"></script>
<script src="{{url("/")}}/public/jscode/config.js" type="text/javascript"></script>

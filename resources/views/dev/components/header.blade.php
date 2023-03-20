<!DOCTYPE html>
<html dir="{{(config('locale') == "en")?"ltr":"rtl"}}" data-dark_mode="{{config('dark_mode')}}">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>{{$meta_title}}</title>

    <link rel="shortcut icon" href="{{showContent("logo_and_icon.icon",true)}}" type="image/x-icon">

    @include('global_components.header_components')

</head>
<body>

    @include("dev.components.navigation_header")

    <?php if(config('menu_display') == "navbar"): ?>
        @include("dev.components.navigation_menu")
    <?php endif; ?>

    @include('global_components.hidden_inputs')

    @include('global_components.toastr_msg')

    <?php if($current_user->user_id == 1): ?>
        @include('dev.components.update_is_available')
        @include('dev.components.modals.confirm_update_version_modal')
    <?php endif; ?>

    @include('global_components.modals.confirm_modal')
    @include('global_components.modals.thanks_modal')
    @include('global_components.modals.errors_modal')
    @include('global_components.modals.server_msg_modal')


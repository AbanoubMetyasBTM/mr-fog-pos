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


    @include("general_form_blocks.main_btm_select_2")


    @include('global_components.hidden_inputs')
    @include('global_components.toastr_msg')
    @include('global_components.modals.server_msg_modal')
    @include('global_components.modals.confirm_modal')
    @include('global_components.modals.thanks_modal')
    @include('global_components.modals.errors_modal')
    @include('global_components.modals.show_data_modal')


    <input type="hidden" class="socket_link" value="{{env("SOCKET_LINK")}}">
    <input type="hidden" class="btm_select2_socket_link" value="{{env("btm_select2_socket_link")}}">


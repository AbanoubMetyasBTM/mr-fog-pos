<script src="{{url("/")}}/public/admin/lib/popper.js/js/popper.js"></script>
<script src="{{url("/")}}/public/admin/lib/bootstrap/js/bootstrap.js"></script>
<script src="{{url("/")}}/public/admin/lib/jquery.cookie/js/jquery.cookie.js"></script>
<script src="{{url("/")}}/public/admin/lib/jquery-toggles/js/toggles.min.js"></script>
<script src="{{url("/")}}/public/admin/js/slim.js"></script>
<script src="{{url("/")}}/public/admin/lib/datatables/js/jquery.dataTables.js"></script>
<script src="{{url("/")}}/public/admin/lib/datatables-responsive/js/dataTables.responsive.js"></script>
<script src="{{url("/")}}/public/admin/lib/select2/js/select2.full.min.js"></script>
<script src="{{url("/")}}/public/btm-select-2/btm-select-2.js"></script>

<script src="{{url("/")}}/public/admin/lib/moment/js/moment.js"></script>
<script src="{{url("/")}}/public/admin/lib/bootstrap-datetimepicker/bootstrap-datetimepicker.js"></script>
<script src="{{url("/")}}/public/admin/lib/summernote/js/summernote-bs4.min.js"></script>

<script src="{{url("/public/ckeditor/ckeditor.js")}}" type="text/javascript"></script>
<script src="{{url("/public/ckeditor/adapters/jquery.js")}}" type="text/javascript"></script>

<!-- other includes -->
@yield('additional_js')

@include('global_components.datatable.scripts')

<!-- Toastr -->
<script src="{{url("/")}}/public/toastr/toastr.js"></script>

<script src="{{url("/")}}/public/admin/lib/jquery.steps/js/jquery.steps.js"></script>
<script src="{{url("/")}}/public/admin/lib/parsleyjs/js/parsley.js"></script>
<script src="{{url("/")}}/public/admin/lib/jquery-ui/js/jquery-ui.js"></script>

<script src="{{url("/")}}/public/sweet_alert/sweetalert2.min.js"></script>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{env("hotelSearchGoogleMapKey", "")}}&libraries=places&sensor=false"></script>
<script src="{{url('public/jscode/admin/map.js')}}"></script>

<script src="{{url("/")}}/public/jscode/admin/theme.js" type="text/javascript"></script>
<script src="{{url("/")}}/public/jscode/admin/utility.js" type="text/javascript"></script>
<script src="{{url("/")}}/public/jscode/admin/admin.js" type="text/javascript"></script>
<script src="{{url("/")}}/public/jscode/admin/settings.js" type="text/javascript"></script>
<script src="{{url("/")}}/public/jscode/admin/product.js" type="text/javascript"></script>
<script src="{{url("/")}}/public/jscode/admin/supplier_order.js" type="text/javascript"></script>
<script src="{{url("/")}}/public/jscode/admin/client_order.js" type="text/javascript"></script>
<script src="{{url("/")}}/public/jscode/admin/gift_card.js" type="text/javascript"></script>
<script src="{{url("/")}}/public/jscode/admin/branch.js" type="text/javascript"></script>
<script src="{{url("/")}}/public/jscode/admin/coupon.js" type="text/javascript"></script>
<script src="{{url("/")}}/public/jscode/admin/login_logout.js" type="text/javascript"></script>
<script src="{{url("/")}}/public/jscode/routes.js" type="text/javascript"></script>
<script src="{{url("/")}}/public/jscode/btm_form_helpers/form.js" type="text/javascript"></script>
<script src="{{url("/")}}/public/jscode/admin/bulk_save.js" type="text/javascript"></script>
<script src="{{url("/")}}/public/jscode/datatable.js" type="text/javascript"></script>

<script src="{{url("/")}}/public/js/google-chars-loader.js" type="text/javascript"></script>
<script src="{{url("/")}}/public/jscode/admin/charts.js" type="text/javascript"></script>

<script src="{{url("/")}}/public/jscode/admin/gift_card_template.js" type="text/javascript"></script>



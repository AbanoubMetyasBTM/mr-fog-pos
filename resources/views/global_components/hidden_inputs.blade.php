<input type="hidden" class="csrf_input_class" value="{{csrf_token()}}">
<input type="hidden" class="url_class" value="{{url("/")}}">
<input type="hidden" class="lang_url_class" value="{{$lang_url_segment??""}}">

<input type="hidden" class="sweet_alert_confirmation_msg" value="{{showContent("general_keywords.are_your_sure")}}">
<input type="hidden" class="sweet_alert_confirmation_yes" value="{{showContent("general_keywords.yes")}}">
<input type="hidden" class="sweet_alert_confirmation_no" value="{{showContent("general_keywords.no")}}">
<input type="hidden" class="disable_socket_as_route" value="yes">

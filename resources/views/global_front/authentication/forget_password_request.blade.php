<form action="{{langUrl("/forget-password-request")}}" method="post" class="ajax_form">

    <div class="input-box">
        <label class="label-text">{!! showContent("general_keywords.email") !!}</label>
        <div class="form-group">
            <span class="la la-envelope-o form-icon"></span>
            <input class="form-control" type="email" required name="email" placeholder="{!! showContent("authentication.enter_email_address") !!}">
        </div>
    </div>

    <div class="my-2">
        @include("global_components.recaptch_block")
    </div>

    {!! csrf_field() !!}

    <div class="btn-box">
        <button type="submit" name="submit_type" value="verify" class="theme-btn">
            {!! showContent("general_keywords.send") !!}
        </button>
    </div>

</form>

<hr>

<div class="btn-box">
    <a href="{{langUrl("login")}}"  class="theme-btn theme-btn-gray btn-xs">
        {!! showContent("general_keywords.login") !!}
    </a>
</div>

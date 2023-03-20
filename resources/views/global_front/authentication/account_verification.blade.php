
<form action="{{langUrl("/account-verification")}}" method="post" class="ajax_form">

    <div class="input-box">
        <label class="label-text">{!! showContent("general_keywords.email") !!}</label>
        <div class="form-group">
            <span class="la la-envelope-o form-icon"></span>
            <input class="form-control" type="email" name="email" required value="{{$email_value}}" placeholder="{!! showContent("authentication.enter_email_address") !!}">
        </div>
    </div>

    <div class="input-box">
        <label class="label-text">{!! showContent("authentication.verification_code") !!}</label>
        <div class="form-group">
            <span class="la la-key form-icon"></span>
            <input class="form-control" type="text" name="verification_code" required placeholder="{!! showContent("authentication.enter_verification_code") !!}">
        </div>
    </div>

    {!! csrf_field() !!}

    <div class="my-2">
        @include("global_components.recaptch_block")
    </div>

    <div class="btn-box">
        <button type="submit" name="submit_type" value="verify" class="theme-btn">
            {!! showContent("authentication.verify") !!}
        </button>
    </div>

    <hr>

    <div class="btn-box">
        <button type="submit" name="submit_type" value="resend" class="theme-btn theme-btn-gray btn-xs">
            {!! showContent("authentication.resend_verification_code") !!}
        </button>
    </div>

</form>


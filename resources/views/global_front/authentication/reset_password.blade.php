<form action="{{langUrl("/reset-password")}}" method="post" class="ajax_form">

    <div class="input-box">
        <label class="label-text">{!! showContent("general_keywords.email") !!}</label>
        <div class="form-group">
            <span class="la la-envelope-o form-icon"></span>
            <input class="form-control" type="email" name="email" value="{{$email_value}}" required placeholder="{!! showContent("authentication.enter_email_address") !!}">
        </div>
    </div>

    <div class="input-box">
        <label class="label-text">{!! showContent("authentication.forget_password_code") !!}</label>
        <div class="form-group">
            <span class="la la-key form-icon"></span>
            <input class="form-control" type="text" name="reset_code" required placeholder="{!! showContent("authentication.forget_password_code") !!}">
        </div>
    </div>

    <div class="input-box">
        <label class="label-text">{!! showContent("general_keywords.password") !!}</label>
        <div class="form-group">
            <span class="la la-key form-icon"></span>
            <input class="form-control" type="password" name="password" required placeholder="{!! showContent("authentication.enter_your_password") !!}">
        </div>
    </div>

    <div class="input-box">
        <label class="label-text">{!! showContent("authentication.confirm_your_password") !!}</label>
        <div class="form-group">
            <span class="la la-key form-icon"></span>
            <input class="form-control" type="password" name="password_confirmation" required placeholder="{!! showContent("authentication.confirm_your_password") !!}">
        </div>
    </div>



    {!! csrf_field() !!}

    <div class="btn-box">
        <button type="submit" name="submit_type" value="verify" class="theme-btn">
            {!! showContent("general_keywords.send") !!}
        </button>
    </div>

</form>

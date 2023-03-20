<form action="{{langUrl("/login")}}" method="post" class="ajax_form">

    <div class="input-box">
        <label class="label-text">{!! showContent("general_keywords.email") !!}</label>
        <div class="form-group">
            <span class="la la-envelope-o form-icon"></span>
            <input class="form-control" type="email" required name="field" placeholder="{!! showContent("authentication.enter_email_address") !!}">
        </div>
    </div>

    <div class="input-box">
        <label class="label-text">{!! showContent("general_keywords.password") !!}</label>
        <div class="form-group">
            <span class="la la-key form-icon"></span>
            <input class="form-control" type="password" required name="password" placeholder="{!! showContent("authentication.enter_email_password") !!}">
        </div>
    </div>



    {!! csrf_field() !!}

    <div class="btn-box">
        <button type="submit" class="btn">{!! showContent("general_keywords.login") !!}</button>
    </div>
</form>

<hr>

<div class="btn-box">
    <a href="{{langUrl("forget-password-request")}}"  class="theme-btn theme-btn-gray btn-xs">
        {!! showContent("authentication.forget_password") !!}
    </a>
</div>

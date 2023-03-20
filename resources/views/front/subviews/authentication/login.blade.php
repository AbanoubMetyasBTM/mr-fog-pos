<div class="signin-wrapper">

    <div class="signin-box">
        <h2 class="slim-logo">
            <a href="{{url("/")}}">
                <img src="{{url("/")}}/public/images/logo.png" style="width: 142px;height: 64px;">
            </a>
        </h2>
        <h2 class="signin-title-primary">Welcome</h2>
        <h4 class="signin-title-secondary">Enter your credentials below</h4>

        <form action="{{url("login")}}" method="POST">
            <div class="form-group">
                @include('global_components.inline_error_msgs')
            </div><!-- form-group -->
            <div class="form-group">
                <input type="text" class="form-control" name="field" required placeholder="Your email address">
            </div><!-- form-group -->
            <div class="form-group mg-b-20">
                <input type="password" class="form-control" name="password" required placeholder="Your password">
            </div><!-- form-group -->
            <div class="form-group mg-b-20">
                <div class="option-row-wrapper">
                    <label class="slim-options-label">Remember me : </label>
                    <div>
                        <label class="rdiobox">
                            <input name="remember_me" checked class="sticky-sidebar" type="radio" value="true">
                            <span>Yes</span>
                        </label>
                        <label class="rdiobox">
                            <input name="remember_me" class="sticky-sidebar" type="radio" value="false">
                            <span>No</span>
                        </label>
                    </div>
                </div>
            </div><!-- form-group -->
            {{csrf_field()}}
            <button type="submit" class="btn btn-primary btn-block btn-signin">Login</button>
        </form>
        <?php if(false): ?>
        <p class="mg-b-0">
            Don't remember your password ?
            <a href="{{url("")}}">Reset Password</a>
        </p>
        <?php endif; ?>
    </div><!-- signin-box -->

</div>


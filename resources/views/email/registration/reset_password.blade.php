@extends("email.main_layout")
@section("subview")

    <table align="center" cellpadding="0" cellspacing="0" style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; box-sizing: border-box; width: 90%; margin: 0 auto; padding: 0;" bgcolor="#FFFFFF" width="570px">
        <!-- Body content -->
        <tr>
            <td style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 35px;">
                <h1 style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; box-sizing: border-box; margin-top: 0; color: #2F3133; font-size: 19px; font-weight: bold;" align="left">
                    {!! showContent("general_keywords.welcome") !!}
                    {{$user_obj->full_name}},
                </h1>

                <p style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; font-size: 16px;" align="left">
                    {!! showContent("authentication.this_is_your_reset_password") !!}
                    <b>"{{$password_reset_code}}"</b>
                </p>

                <p style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; color: #74787E; font-size: 16px;" align="left">
                    Dependably Yours,<br>
                    Mr Fog Support Team
                </p>
            </td>
        </tr>
    </table>

@endsection

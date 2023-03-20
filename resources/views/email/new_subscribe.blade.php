@extends("email.main_layout")
@section("subview")

    <table align="center" cellpadding="0" cellspacing="0" style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; box-sizing: border-box; width: 90%; margin: 0 auto; padding: 0;" bgcolor="#FFFFFF" width="570px">
        <!-- Body content -->
        <tr>
            <td style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 35px;">
                <h1 style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; box-sizing: border-box; margin-top: 0; color: #2F3133; font-size: 19px; font-weight: bold;" align="left">
                    {!! showContent("general_keywords.welcome") !!}
                </h1>

                <p style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; font-size: 16px;" align="left">
                    {!! showContent("authentication.you_have_been_successfully_subscribed_into_our_system") !!}
                </p>

                <p style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; color: #74787E; font-size: 16px;" align="left">
                    Dependably Yours,<br>
                    Mr Fog Support Team
                </p>
            </td>
        </tr>
    </table>

@endsection

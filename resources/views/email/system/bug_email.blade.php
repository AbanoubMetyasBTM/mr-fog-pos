@extends("email.main_layout")
@section("subview")

    <table align="center" cellpadding="0" cellspacing="0" style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; box-sizing: border-box; width: 90%; margin: 0 auto; padding: 0;" bgcolor="#FFFFFF" width="570px">
        <!-- Body content -->
        <tr>
            <td style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 35px;">
                <h1 style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; box-sizing: border-box; margin-top: 0; color: #2F3133; font-size: 19px; font-weight: bold;" align="left">
                    {!! $header !!}
                </h1>

                <p style="color: blue; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; font-size: 16px;" align="left">
                    {!! $message !!}
                </p>

                <br><br>

                <h1 style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; box-sizing: border-box; margin-top: 0; color: #2F3133; font-size: 19px; font-weight: bold;" align="left">Request Details</h1>
                <table style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; box-sizing: border-box;">
                    <?php foreach($getLog as $key => $value): ?>

                    <tr>
                        <th style="width: 14%; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; box-sizing: border-box;">
                            {{$key}}</th>
                        <td style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 5px;" bgcolor="#eee">
                            {{$value}}</td>
                    </tr>
                    <?php endforeach; ?>

                </table>

                <br><br>

                <p style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; color: #74787E; font-size: 16px;" align="left">
                    Cheers,<br>
                    {{capitalize_string(env('APP_NAME'))}}
                    Team
                </p>
            </td>
        </tr>
    </table>

@endsection

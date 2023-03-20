<?php
    $siteNameValue = (isset($siteName) && !empty($siteName)) ? : capitalize_string(env('APP_NAME'));
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>Welcome to, {{env('APP_NAME')}}</title>
</head>

<body style="width: 100% !important; height: 100%; line-height: 1.4; color: #74787E; -webkit-text-size-adjust: none; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; box-sizing: border-box; margin: 0;" bgcolor="#F2F4F6">

<table width="100%" cellpadding="0" cellspacing="0" style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; box-sizing: border-box; width: 100%; margin: 0; padding: 0;" bgcolor="#F2F4F6">
    <tr>
        <td align="center" style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word;">
            <table width="100%" cellpadding="0" cellspacing="0" style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; box-sizing: border-box; width: 100%; margin: 0; padding: 0;">
                <tr>
                    <td style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 25px 0;" align="center">
                        <a href="{{url("/")}}" style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; box-sizing: border-box; color: #bbbfc3; font-size: 16px; font-weight: bold; text-decoration: none; text-shadow: 0 1px 0 white;">
                            <img
                                src="{{ (isset($siteLogo) && !empty($siteLogo)) ? $siteLogo : showContent("logo_and_icon.logo",true)}}"
                                 alt="{{ $siteNameValue }}"
                                 title="{{ $siteNameValue }}"
                                style="width: 200px; height: 70px; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; box-sizing: border-box; border: none;"/>
                        </a>
                    </td>
                </tr>
                <!-- Email Body -->
                <tr>
                    <td width="100%" cellpadding="0" cellspacing="0" style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; border-top-width: 1px; border-top-color: #EDEFF2; border-top-style: solid; border-bottom-width: 1px; border-bottom-color: #EDEFF2; border-bottom-style: solid; margin: 0; padding: 25px;" bgcolor="#FFFFFF">
                        @yield('subview')
                    </td>
                </tr>
                <tr>
                    <td style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word;">
                        <table align="center" width="570" cellpadding="0" cellspacing="0" style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; box-sizing: border-box; width: 570px; text-align: center; margin: 0 auto; padding: 0;">
                            <tr>
                                <td align="center" style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; box-sizing: border-box; word-break: break-word; padding: 35px;">
                                    <p style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; color: #AEAEAE; margin-top: 0; font-size: 12px;" align="center">
                                        © {{date("Y")}} {{ $siteNameValue }} All rights reserved.</p>
                                    <p style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; color: #AEAEAE; margin-top: 0; font-size: 12px;" align="center">
                                        [{{ $siteNameValue }}, LLC]
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>

<!DOCTYPE html>
<html>

<head>
    <title>Welcome</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://fonts.googleapis.com/css?family=Muli:300,400,600,700,800" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,800" rel="stylesheet">
    <style>
        html,
        body {
            padding: 0 !important;
            margin: 0 auto !important;
            height: 100% !important;
            width: 100% !important;
        }

        * {
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        a {
            text-decoration: none;
        }
    </style>
</head>

<body width="100%" style="margin: 0 !important;padding: 0 !important; mso-line-height-rule: exactly;background: #fcfcfc;">
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;border-collapse: collapse;font-weight:400;font-family: Montserrat, sans-serif;background: #fcfcfc;">
        <tbody>
            <tr>
                <td style="text-align: center;vertical-align: middle;padding-top:40px;padding-bottom:40px;">
                    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="600" bgcolor="#ffffff" style="width: 600px;max-width: 100%;border-collapse: collapse;margin-left:auto;margin-right:auto;border: 1px solid #dcdcdc;background:#ffffff;">
                        <tbody>
                            <!-- Emailer Logo Start -->
                            <tr>
                                <td class="emailer-logo" style="padding-top:20px;padding-left:20px; padding-right:20px;padding-bottom:20px;text-align: center;">
                                    <img src="{{ asset('storage/images/logo.png') }}" width="220" height="73" alt="NEQQO Logo" style="display: block;margin-left: auto;margin-right: auto;max-width:60%;height: auto;" />
                                </td>
                            </tr>
                            <!-- Emailer Logo End -->
                            <!-- Emailer Header Start -->
                            <tr>
                                <td class="emailer-header" style="padding-left:20px; padding-right:20px;padding-bottom:20px;">
                                    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin:0 auto;width:100%;border-collapse: collapse;border:0px solid transparent;">
                                        <tbody>
                                            <tr>
                                                <td style="text-align: center;font-family: Montserrat, sans-serif;color:#2E7D32;font-size: 20px;font-weight: 800;">Dear, <span style="text-transform:capitalize;">{{ $mailData['user_name'] }}</span></td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: center;vertical-align: middle;padding-top:20px;">
                                                    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="520" style="margin:0 auto;width:520px;max-width:100%;border-collapse: collapse;border:0px solid transparent;">
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="2" style="font-family: Montserrat, sans-serif;padding-bottom:20px;text-align:center;vertical-align:middle;font-size: 13px;line-height: 1.5;color: #232323; font-weight:500;">Your login Credentials</td>
                                                            </tr>
                                                            <tr>
                                                                <td style="text-align: center;font-family: Montserrat, sans-serif;color:#024683;font-size: 13px;font-weight: 500;line-height: 18px;padding-bottom:10px;">
                                                                    <table cellpadding="5" cellspacing="0" border="1" width="100%" style="margin:0 auto;width:100%;border-collapse: collapse;">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>User Id</th>
                                                                                <th>Password</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>{{ $mailData['user_id'] }}</td>
                                                                                <td>{{ $mailData['password'] }}</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <!-- Emailer Header End -->
                            <!-- Emailer Body Start -->
                            <tr>
                                <td class="emailer-body" style="padding-left:20px;padding-right:20px;padding-bottom:20px;">
                                    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin:0 auto;width:100%;border-collapse: collapse;border:0px solid transparent;">
                                        <tbody>
                                            <tr>
                                                <td style="padding-bottom:20px;text-align: center;vertical-align: middle;font-family: Montserrat, sans-serif;color:#232323;font-size: 13px;font-weight: 500;line-height: 1.5;">Please access the below account URL and login with above credentials to access the NEQQO</td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: center;vertical-align: middle;">
                                                    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="160" style="margin:0 auto;width:160px;max-width:100%;border-collapse: collapse;border:0px solid transparent;">
                                                        <tbody>
                                                            <tr>
                                                                <td style="height:40px;vertical-align:middle;text-transform:uppercase;letter-spacing:0.5px;text-align:center;font-size:13px;">
                                                                    <a href="{{ $mailData['link'] }}" style="display:block;font-family:Montserrat, sans-serif;background:#26a4d3;height:40px;line-height:40px;border-radius:30px;border:0px solid transparent;color:#ffffff;font-weight:300;font-size:13px;text-align:center;text-decoration:none;-webkit-text-size-adjust:none;mso-hide:all;">Account Url</a>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <!-- Emailer Body End -->
                            <!-- Emailer Footer Start -->
                            <tr>
                                <td class="emailer-footer">
                                    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin:0 auto;width:100%;border-collapse: collapse;border:0px solid transparent;">
                                        <tbody>
                                            <tr>
                                                <td style="background-color:#efefef;padding-left:20px;padding-right:20px;padding-top:20px;padding-bottom:20px;">
                                                    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin:0 auto;width:100%;border-collapse: collapse;border:0px solid transparent;">
                                                        <tbody>
                                                            <tr>
                                                                <td style="padding-bottom:20px;text-align: center;vertical-align: middle;font-family: Montserrat, sans-serif;color:#232323;font-size: 13px;font-weight: 600;line-height: 1.5;">Need help? Check out our quick User Guide for answers to common questions.</td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-bottom:20px;text-align: center;vertical-align: middle;font-family: Montserrat, sans-serif;color:#676767;font-size: 13px;font-weight: 400;line-height: 1.5;">Get in touch with one of our experts, you can reach us at <a style="text-decoration: underline;color:#232323;" href="mailto:support@mattsenkumar.com">support@mattsenkumar.com</a> or visit our Online Support Center anytime, day or night, to resolve your query.</td>
                                                            </tr>
                                                            <tr>
                                                                <td style="text-align: center;vertical-align: middle;font-family: Montserrat, sans-serif;color:#ff6e40;font-size: 13px;font-weight: 400;line-height: 1.5;">This is an automatically generated email, please do not reply</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="background-color: #000;padding-left:20px;padding-right:20px;padding-top:20px;padding-bottom:20px;">
                                                    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin:0 auto;width:100%;border-collapse: collapse;border:0px solid transparent;">
                                                        <tbody>
                                                            <tr>
                                                                <td style="padding-bottom:6px;text-align: center;vertical-align: middle;font-family: Montserrat, sans-serif;color:#ffffff;font-size: 13px;font-weight: 600;line-height: 1.5;">Our Mailing Address</td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-bottom:20px;text-align: center;vertical-align: middle;font-family: Montserrat, sans-serif;color:#ffffff;font-size: 13px;font-weight: 400;line-height: 1.5;">576 Glatt Circle, Woodburn, OR, Zip - 97071, USA</td>
                                                            </tr>
                                                            <tr>
                                                                <td style="text-align: center;vertical-align: middle;">
                                                                    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="114" style="margin:0 auto;width:114px;border-collapse: collapse;border:0px solid transparent;">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td style="text-align: center;vertical-align: middle;padding-left:3px;padding-right:3px;">
                                                                                    <a href="#" style="color:#ffffff;text-decoration:none;display: block;">
                                                                                        <img style="display:block;margin: 0;width:32px;height:32px;border-radius: 50%;font-size: 0px;background:#000000;" src="{{ asset('storage/images/facebook.png') }}" alt="facebook">
                                                                                    </a>
                                                                                </td>
                                                                                <td style="text-align: center;vertical-align: middle;padding-left:3px;padding-right:3px;">
                                                                                    <a href="#" style="color:#ffffff;text-decoration:none;display: block;">
                                                                                        <img style="display:block;margin: 0;width:32px;height:32px;border-radius: 50%;font-size: 0px;background:#000000;" src="{{ asset('storage/images/twitter.png') }}" alt="twitter"></a>
                                                                                </td>
                                                                                <td style="text-align: center;vertical-align: middle;padding-left:3px;padding-right:3px;">
                                                                                    <a href="#" style="color:#ffffff;text-decoration:none;display: block;">
                                                                                        <img style="display:block;margin: 0;width:32px;height:32px;border-radius: 50%;font-size: 0px;background:#000000;" src="{{ asset('storage/images/kalbos.png') }}" alt="kalbos">
                                                                                    </a>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="background-color:#464646;padding-left:20px;padding-right:20px;padding-top:12px;padding-bottom:12px;">
                                                    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin:0 auto;width:100%;border-collapse: collapse;border:0px solid transparent;">
                                                        <tbody>
                                                            <tr>
                                                                <td style="text-align: center;vertical-align: middle;font-family: Montserrat, sans-serif;color:#ffffff;font-size: 11px;font-weight: 400;line-height: 1.5;">Copyright © 2019, NEQQO, All rights reserved.</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <!-- Emailer Footer End -->
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
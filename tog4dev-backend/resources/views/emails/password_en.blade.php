<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Together for Intermediation Services (Together for Development) | شركة معاً للوساطة التجارية (معاً للتنمية)</title>
    <style type="text/css">
        body {
            margin: 0;
            padding: 0;
            background: #f0f5f4;
            font-family: Arial, sans-serif;
            color: #000;
        }

        p {
            margin: 0;
            padding: 0;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .header-bg {
            background: linear-gradient(to bottom, #0f7969, #f0f5f4);
            background-color: #0f7969;
            padding: 20px;
            border-radius: 0px 0px 30px 30px;
        }

        .header-bg img {
            width: 200px;
            height: auto;
        }

        h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #000;
        }

        p {
            font-size: 16px;
            color: #333;
            margin-bottom: 10px;
        }

        .content-section {
            padding: 30px 20px;
        }

        .content-section p {
            font-size: 14px;
            color: #333;
            line-height: 1.6;
        }

        .project-details {
            background: #f0f5f4;
            padding: 20px;
            border-radius: 10px;
        }

        .detail-row {
            font-size: 14px;
            margin-bottom: 10px;
            border-radius: 10px;
            background: #fff;
            font-weight: bold;
            padding: 8px 0;
            border-bottom: 1px solid #ddd;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .label {
            display: inline-block;
            padding: 7px;
            width: 150px
        }

        .value {
            color: #333;
        }

        .explore-btn {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #fcca00;
            color: #000;
            font-weight: bold;
            border-radius: 5px;
            font-size: 14px;
        }

        .footer {
            background-color: #0f7969;
            color: #fff;
            padding: 30px;
            text-align: center;
        }

        .footer .social-links {
            font-size: 14px;
            margin-top: 15px;
            margin-bottom: 10px;
        }

        .footer .social-links a {
            color: #fff;
            margin: 0 5px;
        }

        .footer p {
            font-size: 12px;
            color: #fff;
        }

        @media only screen and (max-width: 600px) {
            .container {
                width: 100% !important;
                padding: 15px;
            }

            .header-bg img {
                width: 150px;
            }

            .content-section {
                padding: 20px;
            }
        }
    </style>
</head>

<body style="margin:0; padding:0;">

    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background:#f0f5f4;">
        <tr>
            <td align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="600" style="width:600px; max-width:100%;">
                    <tr>
                        <td align="center" class="header-bg">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center" class="header-logo">
                                        <img src="{{ asset('img/logo-white.png') }}" alt="Logo"
                                            style="margin-bottom:20px;">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" class="header-image">
                                        <img src="{{ asset('img/heroContainer.png') }}" alt="Hero Image"
                                            style="width: auto;">
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" class="content-section">
                            <p>Dear {{ $user->first_name }} {{ $user->last_name}},</p>
                            <p>Thank you for joining T4D! Your account has been successfully created. Below are your login details:</p>
                            <p>Email: <strong>{{ $user->email}}</strong></p>
                            <p>Password: <strong>{{ $password }}</strong></p>
                            <p>Please keep this information safe and secure. For your convenience, you can log in using the following link:</p>
                            <p><a href="https://tog4dev.com/en/login">tog4dev.com</a></p>
                            <p>Welcome to the community,</p>
                            <p><strong>Together for Intermediation Services Team</strong></p>
                        </td>
                    <tr>
                        <td align="center">
                            @include('includes.email-footer-en')
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>
</body>

</html>

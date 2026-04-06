<!DOCTYPE html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Subscription Cancellation Confirmation</title>
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

        .button {
            display: inline-block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #0f7969;
            color: #fff !important;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
        }

        .button:hover {
            background-color: #0d675b;
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
                        <h3>Dear {{ $name }},</h3>
                        <p>
                            We would like to inform you that, as of
                            <strong>{{ $cancellation_date }}</strong>
                            , your monthly subscription
                            <strong>"{{ $title_en }}"</strong>
                            has been canceled.
                        </p>

                        <p>During your subscription, your delegation ({{ $beneficiaries_msg }}), with a day-by-day impact on families.</p>
                        <p>We understand that circumstances change and appreciate the trust you placed in us. You can reactivate your subscription at any time via our website or explore our other projects.</p>
                        <p>Thank you for your trust. We look forward to your return whenever you’re ready to resume the service.</p>
                        <p>Best regards,</p>
                        <p><strong>Together for Development Team</strong></p>
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
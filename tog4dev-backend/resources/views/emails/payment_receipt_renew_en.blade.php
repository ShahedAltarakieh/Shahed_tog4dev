<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Email Delegate</title>
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
                            <p>
                                Thank you <strong>{{ $user->first_name }} {{ $user->last_name }}</strong> for your continued trust.
                            </p>
                            <p>
                                Your monthly delegation has been successfully renewed. Your presence with us means so much to us and to the families benefiting from our projects around the world.
                            </p>
                            @if($country && !empty($country["country_name_english"]))
                                <p style="margin-top:10px">
                                    <img src="{{ asset('img/pin.png') }}" style="width: 16px">
                                     {{ $country["country_name_english"] }}
                                </p>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding:20px;padding-top:0px;">
                        <h2 style="margin-top:0;font-size: 18px;">Details of the Delegated Project for Together for Intermediation Services</h2>
                        <table border="1" cellpadding="5" cellspacing="0" style="width:100%; border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th>Delegated Project Title</th>
                                    <th>Country of Execution</th>
                                    <th>Unit Price</th>
                                    <th>Quantity</th>
                                    <th>Payment Type</th>
                                    <th>Delegation date</th>
                                    <th>Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalAmount = 0; // Initialize total amount variable
                                @endphp
                                @foreach ($carts as $cart)
                                    <tr>
                                        <td style="text-align:left">{{ $cart->title_en ?? 'Not Found' }}</td> <!-- Project Name -->
                                        <td style="text-align:left">{{ $cart->location_en ?? 'Unknown' }}</td> <!-- Project Name -->
                                        <td style="text-align:left">{{ (new \App\Helpers\Helper)->formatNumber($cart->price / $cart->quantity) }}JOD</td> <!-- Price -->
                                        <td style="text-align:center">{{ $cart->quantity }}</td>
                                        <td style="text-align:left">{{ $cart->type == 'one_time' ? 'One Time' : "Subscription" }}</td> <!-- Payment Type -->
                                        <td style="text-align:center">{{ date('Y/m/d', strtotime($payment->created_at)) }}</td>
                                        <td style="text-align:left">{{ (new \App\Helpers\Helper)->formatNumber($cart->price) }}JOD</td> <!-- Price -->
                                    </tr>
                                    @php
                                        $totalAmount += $cart->price; // Add cart price to total
                                    @endphp
                                @endforeach
                                <tr>
                                    <td style="text-align:right; font-weight:bold;" colspan="6">Total cost</td>
                                    <td style="text-align:left; font-weight:bold;">{{ (new \App\Helpers\Helper)->formatNumber($totalAmount) }}JOD</td> <!-- Total Amount -->
                                </tr>
                            </tbody>
                        </table>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" class="content-section">
                            <p>
                                Through your delegation, we are able to continue our projects and provide essential support to families, enabling us to achieve sustainable positive impact in the community together.
                            </p>
                            <p>
                                For more information about our projects or to get in touch, feel free to contact us
                            </p>
                        </td>
                    </tr>
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

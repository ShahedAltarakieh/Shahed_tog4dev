<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>توكيل عبر البريد الإلكتروني</title>
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
                                        <img src="{{ asset('img/logo-white.png') }}" alt="الشعار"
                                            style="margin-bottom:20px;">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" class="header-image">
                                        <img src="{{ asset('img/heroContainer.png') }}" alt="صورة بطولية"
                                            style="width: auto;">
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" class="content-section" style="direction:rtl;">
                            <p>
                            شكرًا <strong>{{ $user->first_name }} {{ $user->last_name }}</strong> على ثقتك المستمرة                                
                            </p>
                            <p>تم تجديد توكيلك الشهري بنجاح ، وجودك معنا يعني الكثير لنا وللأسر التي تستفيد من مشاريعنا حول العالم</p>
                            @if($country && !empty($country["country_name_arabic"]))
                                <p style="margin-top:10px">
                                    {{ $country["country_name_arabic"] }}
                                     <img src="{{ asset('img/pin.png') }}" style="width: 16px" alt="pin">
                                </p>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding:20px;padding-top:0;direction:rtl;">
                            <h2 style="margin-top:0;font-size: 18px;">تفاصيل المشروع الموكل لمعاً للوساطة التجارية</h2>
                        <table border="1" cellpadding="5" cellspacing="0" style="width:100%; border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th>اسم المشروع الموكل</th>
                                    <th>دولة التنفيذ</th>
                                    <th>السعر المفرد</th>
                                    <th>العدد</th>
                                    <th>نوع الدفع</th>
                                    <th>تاريخ التوكيل</th>
                                    <th>السعر الإجمالي</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total = 0; // Initialize total
                                @endphp
                                @if(isset($carts))
                                @foreach ($carts as $cart)
                                    <tr>
                                        <td style="text-align:right">{{ $cart->title ?? 'لا يوجد' }}</td> <!-- Project Name -->
                                        <td style="text-align:right">{{ $cart->location ?? 'غير معروف' }}</td> <!-- Project Name -->
                                        <td style="text-align:right;">{{ (new \App\Helpers\Helper)->formatNumber($cart->price / $cart->quantity) }}د.أ</td> <!-- Price -->
                                        <td style="text-align:center">{{ $cart->quantity }}</td>
                                        <td style="text-align:right">{{ $cart->type == 'one_time' ? 'الدفع لمرة واحدة' : "اشتراك شهري" }}</td> <!-- Payment Type -->
                                        <td style="text-align:center">{{ date('Y/m/d', strtotime($payment->created_at)) }}</td>
                                        <td style="text-align:right;">{{ (new \App\Helpers\Helper)->formatNumber($cart->price) }}د.أ</td> <!-- Price -->
                                    </tr>
                                    @php
                                        $total += ($cart->price); // Add to total
                                    @endphp
                                @endforeach
                                @endif
                                <tr>
                                    <td colspan="6" style="text-align:right; font-weight:bold;">التكلفة الكلية</td>
                                    <td style="text-align:right; font-weight:bold;">{{ (new \App\Helpers\Helper)->formatNumber($total) }}د.أ</td> <!-- Display Total -->
                                </tr>
                            </tbody>
                        </table>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" class="content-section">
                            <p>
                                من خلال توكيلك الشهري نتمكن  من مواصلة مشاريعنا حول العالم مما يجعلنا نحقق معًا استدامة الاثر الايجابي في المجتمع
                            </p>
                            <p>
                                لمزيد من المعلومات حول مشاريعنا أو للتواصل، لا تتردد في مراسلتنا 
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td align="center">
                            @include('includes.email-footer-ar')
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>
</body>

</html>

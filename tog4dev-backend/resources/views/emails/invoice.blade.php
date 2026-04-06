<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>إيصال</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.5;
            color: #0f7969;
        }

        .header-section,
        .footer-section {
            width: 100%;
        }

        .footer-section{
            margin-bottom: 0px !important;
        }

        .info-line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .info-line div {
            width: 100%;
        }

        .checkboxes label {
            margin-left: 20px;
            font-size: 14px;
            width: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            vertical-align: middle;
            white-space: nowrap;
        }

        th {
            background-color: #0f7969;
            text-align: center;
            color: #fff;
            font-weight: normal;
        }

        td {
            text-align: right;
        }

        .footer-section {
            margin-top: 10px;
            font-size: 14px;
        }

        .footer-section .signature-line {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .footer-section .signature-line div {
            width: 30%;
            border-top: 1px solid #000;
            text-align: center;
            padding-top: 5px;
            font-size: 13px;
        }

        .invoice {
            margin: 0px !important;
            text-align: center;
            font-weight: bold;
            font-size: 25px;
        }

        .sign {
            text-align: left;
        }
    </style>
</head>

<body>
    <img src="{{ public_path('img/headers.png') }}" alt="Company Header Image" style="width: 100%; height: auto;">

    <div class="header-section">
        <div class="info-line">
            <div>
                وصلنا من: {{$user->first_name ?? 'غير معروف'}} {{$user->last_name ?? ''}}
                &nbsp;&nbsp;|&nbsp;&nbsp; التاريخ: <?php echo date('d/m/Y', strtotime($payment->created_at)); ?>
            </div>
            <div>
                هاتف: {{$user->phone ?? 'غير متوفر'}}
                @if($user->email != "invoice.tog4dev2025@gmail.com" && $user->email != "invoice.tog4dev2024@gmail.com")
                &nbsp;&nbsp;|&nbsp;&nbsp; البريد الإلكتروني: {{$user->email ?? 'غير متوفر'}}
                @endif
            </div>
        </div>

        <div class="checkboxes">
            <label><img width="20" height="20" src="{{ public_path('img/checkbox.png') }}" alt="Logo 2">
            @if(strtolower($payment->payment_type) == "zbooni" || strtolower($payment->payment_type) == "zbooni usa")
            الدفع عن طريق زبوني
            @elseif(strtolower($payment->payment_type) == "orange money")
            الدفع عن طريق محفظة أورانج
            @elseif(strtolower($payment->payment_type) == "cliq" || strtolower($payment->payment_type) == "orange money -t4dg" || strtolower($payment->payment_type) == "orange money -t4dk")
            الدفع عن طريق كليك
            @elseif(strtolower($payment->payment_type) == "visa")
            الدفع عن طريق فيزا
            @elseif(strtolower($payment->payment_type) == "cheque")
            الدفع عن طريق شيك
            @elseif(strtolower($payment->payment_type) == "bank")
            الدفع عن طريق البنك 
            @elseif(strtolower($payment->payment_type) == "cash")
            الدفع نقدا 
            @else
             الدفع عن طريق الموقع الالكتروني
            @endif
            </label>
        </div>
        <p style="font-weight:bold;font-size:12px;color:#222;">
            تُنفذ شركة معاً للوساطة التجارية ذ.م.م (معاً للتنمية) حلولاً ومشاريع تنموية مُخصصة للأفراد والشركات والمؤسسات غير الربحية حصراً عبر عقود خدمات مُتفق عليها، مع التأكيد على أن الشركة لا تقبل أي تبرعات مالية أو عينية بأي شكل من الأشكال، تماشياً مع سياساتها المؤسسية والأنظمة المعمول بها.
        </p>
    </div>
    <div class="invoice">
        <h4>{{ $payment->contract_id ?? '' }}</h4>
    </div>
    <table border="1" cellpadding="5" cellspacing="0"
        style="width:100%; border-collapse: collapse; direction: rtl; text-align: right;">
        <thead>
            <tr>
                <th>فلس</th>
                <th>دينار</th>
                <th colspan="2">المشروع الرئيسي</th>
                <th>التفصيل الفرعي</th>
                <th>العدد</th>
                <th>المبلغ</th>
                <th></th>
                <th colspan="3">تفاصيل الشيكات</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalAmount = 0;
            @endphp
            @if(isset($carts))
                    @foreach ($carts as $cart)
                            <tr>
                                <td>{{ fmod($cart->price, 1) * 1000 }}</td>
                                <td>{{ intval($cart->price) }}</td>
                                <td colspan="2">{{ $cart->title ?? 'غير موجود' }}</td>
                                <td>{{ $cart->description ?? 'غير محدد' }}</td>
                                <td>{{ $cart->quantity ?? 1 }}</td>
                                <td>{{ (new \App\Helpers\Helper)->formatNumber($cart->price / $cart->quantity) }}د.أ</td>
                                <td></td>
                                <td>{{ $cart->check_amount ?? '' }}</td>
                                <td>{{ $cart->check_number ?? '' }}</td>
                                <td>{{ $cart->check_due_date ?? '' }}</td>
                            </tr>
                            @php
                                $totalAmount += $cart->price;
                            @endphp
                    @endforeach
            @endif
            <tr>
                <td>{{ fmod($totalAmount, 1) * 1000  }}</td>
                <td>{{ (new \App\Helpers\Helper)->formatNumber( intval($totalAmount)) }}د.أ</td>
                <td colspan="9" style="text-align:right; font-weight:bold;">المجموع:
                    {{ numberToWords(number: $totalAmount) }} فقط لا غير
                </td>
            </tr>
        </tbody>
    </table>

    <div class="footer-section">
        <div class="info-line">
            <div>
                المستلم: ........................................
                &nbsp;&nbsp;|&nbsp;&nbsp; التدقيق: ........................................
                &nbsp;&nbsp;|&nbsp;&nbsp; أمين الصندوق: ........................................
            </div>
        </div>
        <p style="font-size:12px;margin-top:0px;">
            هذا السند يُعد إثباتًا لعملية شراء خدمات وتوكيل لشركة معاً للوساطة التجارية ذ.م.م (معاً للتنمية)، ولا يترتب على هذه العملية أي إعفاءات ضريبية لمشتري الخدمة.
        </p>
        <div class="sign">
            <img width="100" height="80" src="{{ public_path('img/sign.png') }}" alt="sign 1" style="vertical-align: middle;" />
            &nbsp;&nbsp;&nbsp;
            <img width="160" src="{{ public_path('img/new-sign.png') }}" alt="sign 2" style="vertical-align: middle;"/>
        </div>
    </div>
</body>

</html>

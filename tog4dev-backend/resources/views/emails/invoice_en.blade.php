<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Receipt</title>
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
            text-align: left;
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
            text-align: right;
        }
    </style>
</head>

<body>
    <img src="{{ public_path('img/invoice_headers_en.png') }}" alt="Company Header Image" style="width: 100%; height: auto;">

    <div class="header-section">
        <div class="info-line">
            <div>
                Received from: {{$user->first_name ?? 'Unknown'}} {{$user->last_name ?? ''}}
                &nbsp;&nbsp;|&nbsp;&nbsp; Date: <?php echo date('d/m/Y', strtotime($payment->created_at)); ?>
            </div>
            <div>
                Phone Number: {{$user->phone ?? 'Not available'}}
                @if($user->email != "invoice.tog4dev2025@gmail.com" && $user->email != "invoice.tog4dev2024@gmail.com")
                    &nbsp;&nbsp;|&nbsp;&nbsp; Email: {{$user->email ?? 'Not available'}}
                @endif
            </div>
        </div>

        <div class="checkboxes">
            <label><img width="20" height="20" src="{{ public_path('img/checkbox.png') }}" alt="Paid"> 
            @if(strtolower($payment->payment_type) == "zbooni" || strtolower($payment->payment_type) == "zbooni usa")
                Payment via the Zbooni
            @elseif(strtolower($payment->payment_type) == "orange money")
                Payment via the Orange Money
            @elseif(strtolower($payment->payment_type) == "cliq" || strtolower($payment->payment_type) == "orange money -t4dg" || strtolower($payment->payment_type) == "orange money - t4dk")
                Payment via CliQ
            @elseif(strtolower($payment->payment_type) == "visa")
                Payment via Visa
            @elseif(strtolower($payment->payment_type) == "cheque")
                Payment via Cheque
            @elseif(strtolower($payment->payment_type) == "bank")
                Payment via Bank 
            @elseif(strtolower($payment->payment_type) == "cash")
                Payment Cash
            @else
                Payment via the website
            @endif
            </label>
        </div>
    </div>
    <p style="font-weight:bold;font-size:12px;color:#222;">
    Together for Intermediation Services (Together for Development) Company implements customized development solutions and projects for individuals, companies, and non-profit organizations exclusively through mutually agreed service contracts. We emphasize that the company does not accept any form of donations or in-kind donations, in compliance with our institutional policies and applicable regulations.
    </p>
    <div class="invoice">
        <h4>{{ $payment->contract_id }}</h4>
    </div>

    <table border="1" cellpadding="5" cellspacing="0" style="width:100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr>
                <th>Fils</th>
                <th>Dinar</th>
                <th colspan="2">Main Project</th>
                <th>Sub-detail</th>
                <th>Quantity</th>
                <th>Amount</th>
                <th></th>
                <th colspan="3">Check Details</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalAmount = 0;
            @endphp
            @foreach ($carts as $cart)
                        <tr>
                            <td>{{ fmod($cart->price, 1) * 1000 }}</td>
                            <td>{{ intval($cart->price) }}</td>
                            <td colspan="2">{{ $cart->title_en ?? 'Not Found' }}</td>
                            <td>{{ $cart->description_en ?? 'Not Specified' }}</td>
                            <td>{{ $cart->quantity ?? 1 }}</td>
                            <td>{{ (new \App\Helpers\Helper)->formatNumber($cart->price / $cart->quantity) }}JOD</td>
                            <td></td>
                            <td>{{ $cart->check_amount ?? '' }}</td>
                            <td>{{ $cart->check_number ?? '' }}</td>
                            <td>{{ $cart->check_due_date ?? '' }}</td>
                        </tr>
                        @php
                            $totalAmount += ($cart->price);
                        @endphp
            @endforeach
            <tr>
                <td></td>
                <td>{{ (new \App\Helpers\Helper)->formatNumber($totalAmount) }}JOD</td>
                <td colspan="9" style="text-align:left; font-weight:bold;">Total Amount :
                    Only {{ numberToWords($totalAmount) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer-section">
        <div class="info-line">
            <div>
                Recipient: ........................................
                &nbsp;&nbsp;|&nbsp;&nbsp; Verification: ........................................
                &nbsp;&nbsp;|&nbsp;&nbsp; Treasurer: ........................................
            </div>
        </div>
        <p style="font-size:12px;margin-top:0px;">
            This receipt serves as proof of service purchase and delegation to Together for Intermediation Services (Together for Development) (Together for Water Management Projects LLC). Please note that this transaction does not entitle the purchaser to any tax exemptions.        </p>
        <div class="sign">
            <img width="100" height="80" src="{{ public_path('img/sign.png') }}" alt="sign 1" style="vertical-align: middle;" />
            &nbsp;&nbsp;&nbsp;
            <img width="160" src="{{ public_path('img/new-sign.png') }}" alt="sign 2" style="vertical-align: middle;"/>
        </div>
    </div>
</body>

</html>

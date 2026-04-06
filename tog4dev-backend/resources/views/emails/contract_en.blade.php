<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Authorization Contract</title>
    <style>
        body {
            line-height: 1;
            direction: ltr;
            text-align: left;
        }

        .header {
            display: flex;
            align-items: flex-start;
        }

        .right-logo {
            width: 100px;
        }

        .left-logos {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .left-logos img {
            width: 100px;
        }

        .contract-title {
            font-size: 1.2em;
            font-weight: bold;
            text-align: center;
            clear: both;
        }

        .section {
            line-height: 1;
            margin-bottom: 2px;
            clear: both;
            font-size: small;
        }

        .border span {
            margin: 20px 0;
            font-size: 1em;
            text-align: justify;
            color: black;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto;
            margin-bottom: 3px;
            font-size: 0.6em;
            border: 2px solid #009688;
        }

        th {
            border: 2px solid #009688;
            padding: 2px;
            text-align: center;
        }

        td {
            border: 2px solid #009688;
            padding: 2px;
            text-align: left;
        }

        .border {
            display: inline-block;
            border: 2px solid;
            padding: 3px 5px;
        }

        .border-green {
            border: 5px solid green;
            text-align: center;
        }

        .border p {
            padding: 5px;
        }

        .green-text {
            color: teal;
            font-size: 1em;
        }

        .width-auto {
            width: auto;
        }

        .container {
            width: 80%;
            margin: 0 auto;
        }

        .signature-box {
            display: inline-block;
            border: 1px solid black;
            padding: 3px 5px;
        }

        .qr-code-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 120px;
        }

        .qr-code-label {
            margin-bottom: 5px;
        }

        .qr-code-box {
            border: 1px solid green;
            padding: 10px;
        }

        .qr-code-box img {
            width: 100px;
        }

        .flex-between-center {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-img {
            width: 100px;
            display: flex;
            float: left;
            margin-right: 10px;
        }

        .center-title {
            text-align: center;
            font-size: 2em;
            font-weight: bold;
            color: black;
            margin-bottom: 10px;
        }

        .center-title2 {
            text-align: center;
            font-size: 1.5em;
            color: black;
            margin-bottom: 10px;
        }

        .p-margin-8 {
            margin: 10px;
        }

        .p-teal {
            color: teal;
            font-size: 1em;
            margin: 0;
        }

        .center-subtitle {
            text-align: center;
            font-size: 1.5em;
            color: black;
            margin-bottom: 10px;
        }

        .p-small {
            font-size: 1em;
            margin: 0;
        }

        .justify-text {
            margin: 5px 0;
            line-height: 1;
            font-size: 1em;
            text-align: justify;
            color: black;
        }

        .margin-top-10 {
            margin-top: 5px;
        }

        .align-content-center {
            align-content: center;
            margin-top: 8px;
        }

        .signature-box-width {
            width: 130px;
        }

        .padding-10 {
            padding: 10px;
        }

        .margin-bottom-5 {
            margin-bottom: 5px;
        }

        .margin-bottom-30 {
            margin-bottom: 30px;
        }

        .width-200 {
            width: 200px;
        }

        .width-150 {
            width: 150px;
        }

        .flex-gap-10 {
            display: flex;
            gap: 10px;
        }

        .float-right {
            float: right;
        }

        .float-left {
            float: left;
        }

        .text-center {
            text-align: center;
        }

        /* New Classes Added */

        .no-border-table {
            width: 100%;
            border: none;
            border-collapse: collapse;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .no-border {
            border: none;
        }

        .logo-small {
            width: 50px;
            margin-right: 10px;
        }

        .logo-medium {
            width: 80px;
            margin-right: 10px;
        }

        .logo-large {
            width: 150px;
            margin-left: 10px;
        }

        .follow-us-title {
            display: block;
            font-size: 16px;
            font-weight: bold;
            color: #0f7969;
            margin-bottom: 10px;
        }

        .inline-block-center {
            display: inline-block;
            text-align: center;
        }

        .qr-code-img {
            width: 80px;
            border: 4px solid #0f7969;
            padding: 5px;
            border-radius: 5px;
        }

        .signature-box-inline {
            border: 1px solid #000;
            padding: 5px 10px;
            display: inline-block;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .name-position {
            font-size: 14px;
            line-height: 1.5;
        }

        .follow-us-container {
            text-align: center;
        }

        .date-text {
            font-size: 14px;
        }

        /* Utility Classes */

        .mr-10 {
            margin-right: 10px;
        }

        .ml-10 {
            margin-left: 10px;
        }

        .fw-bold {
            font-weight: bold;
        }

        .color-teal {
            color: teal;
        }
    </style>
</head>

<body>
    <div class="center-title2">
        <p class="p-margin-8">بسم الله الرحمن الرحيم</p>
    </div>
    <table class="no-border-table">
        <tr>
            <td class="text-left no-border">
                <img class="logo-small" src="{{ public_path('img/qatra.png') }}" alt="Logo 2">
                <img class="logo-medium mr-10" src="{{ public_path('img/basar.png') }}" alt="Logo 1">
            </td>
            <td class="text-right no-border">
                <img class="logo-large" src="{{ public_path('img/logo.png') }}" alt="Logo 4">
            </td>
        </tr>
    </table>

    <div class="center-title">
        <p class="p-teal">Services Agency Agreement</p>
    </div>
    <div class="center-subtitle">
        <p class="p-small">Contract No: {{$payment->contract_id ?? ''}}</p>
    </div>

    <div class="justify-text">
        <span class="fw-bold color-teal">Introduction:</span>
        <span>
            The goals of Together for Intermediation Services (Together for Development) Projects Co are to delegate to raising awareness and developing
            the capabilities
            of communities, individuals, institutions and organizations, marketing products that are socially
            responsible for the company, and expanding
            the scope of practicing the social role to serve the community.
        </span>
    </div>

    <div class="section">
        <div class="margin-top-10">
            <span style="font-weight:bold;">
                Together for Intermediation Services (Together for Development) implements customized development solutions and projects for individuals, companies, and non-profit organizations exclusively through mutually agreed service contracts. We emphasize that the company does not accept any form of donations or in-kind donations, in compliance with our institutional policies and applicable regulations.
            </span>
        </div>
    </div>

    <div class="section">
        <span class="green-text">First Party:</span>
        <div class="border margin-top-10">
            <span>Together for Intermediation Services (Together for Development) Projects Co, a limited liability company duly registered with the
                Companies Control Department at the
                Jordanian Ministry of Industry and Trade under No. (62808) dated 2022/08/03, and is represented, for the
                purposes of signing this agreement,
                by the Director General in his capacity as the authorized signatory on behalf of the company or his
                representative (hereinafter referred to
                as “the first party or the company or Together for Intermediation Services”);
            </span>
        </div>
    </div>

    <div class="section">
        <span class="green-text">The second party:</span>
        <span>As shown on page two.</span>
    </div>
    <div class="section">
        <span class="green-text">Article One:</span>
        <span>
            <span class="green-text">1.</span> The second party agreed to consider the first party as a logistical
            intermediary to find the appropriate partners, contractors and suppliers
            to implement the project subject to this agreement
            <span class="green-text">2.</span> Under this agreement, the second party shall delegate Together for Intermediation Services Co. to implement and establish the project described in Article 2. The second party has
            delegated the first party to
            carefully select all elements of project implementation and follow up on its implementation as agreed upon
            in this agreement.
        </span>
    </div>

    <div class="section">
        <span class="green-text">Article Two:</span>
        <span>The project has been agreed upon as described on page two of this agreement.</span>
    </div>

    <div class="section">
        <span class="green-text">Article Three:</span>
        <span>Obligations of the First Party:
            <span class="green-text">1.</span> .The First Party is committed to selecting appropriate partners with
            experience, each in his country, to implement the project works of all
            kinds.
            <span class="green-text">2.</span> .Selecting the location and preparing the requirements, raw materials and
            equipment for implementation and transportation.
            <span class="green-text">3.</span> Adhering to the period stated in the agreement to implement the project
            unless any force majeure or global pandemic occurs that prevents
            adherence to the mentioned time period.
            <span class="green-text">4.</span> Providing the Second Party with documents and papers that show and
            clarify the fact of the
            completion of the project.
            <span class="green-text">5.</span> The First Party shall exercise the required and reasonable care to ensure
            the safety and correctness of the supply
            of the implemented services.
            <span class="green-text">6.</span> The First Party is committed to paying the financial obligations due to
            cover the costs of completing the
            project upon completion.
        </span>
    </div>

    <div class="section">
        <span class="green-text">Article Four:</span>
        <span>Obligations of the Second Party: The Second Party is obligated to pay the amount mentioned on the second
            page or its
            equivalent in local currency to the First Party, as a price for implementing the project with all its
            requirements and a one-year warranty for
            construction projects only, and according to the appendix on the second page of this agreement.
        </span>
    </div>

    <div class="section">
        <span class="green-text">Article Five:</span>
        <span> General Conditions:
            <span class="green-text">1.</span> We at Together for Intermediation Services (Together for Development) Projects Co try to implement
            development and relief projects near the places of the poor, displaced
            persons, refugees, civilians, or disaster-stricken areas in relief situations.
            <span class="green-text">2.</span> When the first party is unable to implement the project after
            contracting due to the lack of appropriate conditions for implementing the project or due to force majeure,
            the company's management tries
            to find appropriate means and alternatives to implement the project within the agreed conditions in another
            place and by alternative
            methods, and in this case it must inform the second party of the additional period of time that the
            procedure may require.
            <span class="green-text">3.</span> The company's
            work ends and it is considered to have fulfilled all its obligations after implementing the project and
            submitting a photo and video report on
            the implementation of the project - unless humanitarian conditions or wars prevent that - and the second
            party is notified of the completion
            of implementation via a WhatsApp message on the number provided to the company, thus the company has
            completed all the terms of the
            agreement in this agreement
            <span class="green-text">4.</span> Construction projects are guaranteed only for one year from the date of
            delivery of the project to the second
            party, and the expected life of the construction projects depends on the conditions of use by the final
            beneficiaries.
            <span class="green-text">5.</span> . Each project is
            implemented by the company or our local partners specialized in each country according to the type of each
            project.
            <span class="green-text">6.</span> In the event that the
            second party cancels the agreement before the start of implementation of the work, 25% of the paid amount
            will be deducted and the
            remainder will be returned. However, in the event that the project starts to be implemented, no amounts paid
            can be refunded and the
            company alone determines this.
            <span class="green-text">7.</span> In the event of any dispute or conflict between the two parties regarding
            the implementation of the terms
            of this agreement and it cannot be resolved amicably, this dispute will be referred to the Jordanian courts,
            the Palace of Justice Court - Abdali.
            The laws in force in the Hashemite Kingdom of Jordan will apply
            <span class="green-text">8.</span> If the Company is unable to deliver the purchased materials or is required 
            to postpone their distribution due to force majeure events or circumstances beyond its control (including, but 
            not limited to, crises, wars, legal restrictions, or requirements imposed by implementing entities), the Company 
            shall have the right, without the need to obtain prior approval from the Second Party, to redistribute the materials 
            to the targeted beneficiaries or to other needy groups within and/or outside the country of implementation, in order
             to prevent damage to or loss of the materials, and without incurring any liability whatsoever in this regard
        </span>
    </div>
    <table class="no-border-table">
        <tr>
            <td class="text-left no-border">
                <div class="follow-us-container">
                    <p class="follow-us-title">Follow Us</p>
                    <div class="inline-block-center">
                        <img class="qr-code-img" src="{{ public_path('img/our_work.jpg') }}" alt="QR Code 1">
                    </div>
                </div>
            </td>
            <td class="text-right no-border">
                <div class="inline-block-center">
                    <!-- Signature Box -->
                    <div class="signature-box-inline">
                        First Party Signature
                    </div>
                    <!-- Name and Position -->
                    <div class="name-position">
                        <span>Rami Abu Al-Samen</span><br>
                        <span>General Manager</span>
                    </div>
                    <div>
                        <img width="90" height="70" src="{{ public_path('img/sign.png') }}" alt="sign" style="vertical-align: middle;" />
                        <img width="160" src="{{ public_path('img/new-sign.png') }}" alt="sign2" style="vertical-align: middle;" />
                    </div>
                </div>
            </td>
        </tr>
    </table>
    <div class="section">
        <div class="contract-title">
            <p>Appendix 1 / Projects</p>
        </div>
        <div class="section">
            <span class="green-text">The second party: :</span>
            <div class="border">
                <span class="padding-10">Mr./Ms. {{$payment->userDetails->first_name ?? ''}}
                    {{$payment->userDetails->last_name ?? ''}}, Country of Residence 
                    @if($country && !empty($country["country_name_english"]))
                        {{ $country["country_name_english"] }}
                    @else 
                        -------
                    @endif
                </span>
            </div>
        </div>
        <table class="no-border-table" border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Project Name</th>
                    <th>Project Type</th>
                    <th>Delegated Project Details</th>
                    <th>Project Country</th>
                    <th>Payment Type</th>
                    <th>Quantity</th>
                    <th>Amount</th>
                    <th>Dedications name</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalAmount = 0; // Initialize total amount variable
                @endphp
                @if (isset($carts))
                            @foreach ($carts as $cart)
                                        <tr>
                                            <td>{{ $cart->title_en ?? 'Not Found' }}</td>
                                            <!-- Project Name -->
                                            <td>{{ $cart->model?->category?->getLocalizationTitle() ?? 'Not Specified' }}
                                            </td>
                                            <!-- Project Type -->
                                            <td>
                                                @php
                                                    $singlePrice = $cart->model?->single_price ?? null;
                                                    $qtyDisplay = ($singlePrice != null && $cart->price > 0)
                                                        ? (new \App\Helpers\Helper)->formatNumber((new \App\Helpers\Helper)->getSinglePriceQty($singlePrice, $cart->price))
                                                        : null;
                                                @endphp
                                                {{ $cart->description_en ?? 'No Description Available' }}
                                                @if($qtyDisplay !== null) 
                                                 - You have delegated us to purchase a quantity of ({{ $qtyDisplay }}) units of this product and supply them based on the collective delegation agreement.
                                                @endif
                                            </td>
                                            <!-- Project Description -->
                                            <td>
                                                {{ $cart->location_en ?? 'Unknown' }}
                                            </td>
                                            <td>{{ $cart->type == 'one_time' ? 'One Time' : "Subscription" }}</td>
                                            <td>{{ $cart->quantity }}</td>
                                            <td>{{ (new \App\Helpers\Helper)->formatNumber($cart->price / $cart->quantity) }}JOD</td>
                                            <td>
                                                @php
                                                    $dedicationsDisplay = $cart->dedications->map(function ($d) {
                                                        $phrase = $d->dedication_phrase_en;
                                                        return $phrase ? $d->name . ' (' . $phrase . ')' : $d->name;
                                                    })->implode(', ');
                                                @endphp
                                                {{ $dedicationsDisplay ?: '—' }}
                                            </td>
                                        </tr>
                                        @php
                                            $totalAmount += ($cart->price); // Add cart price to total
                                        @endphp
                            @endforeach
                @endif
                <tr>
                    <td colspan="7" class="text-right fw-bold">Total Amount</td>
                    <td class="fw-bold">{{ (new \App\Helpers\Helper)->formatNumber($totalAmount) }}JOD</td>
                    <!-- Total Amount -->
                </tr>
            </tbody>
        </table>
    </div>

    <div class="flex-between-center">
        <div class="top-section width-200 flex-gap-10 float-right">
            <div class="signature-box margin-bottom-30">
                <span>Total Contracted Amount (JOD)</span>
            </div>
            <span>{{(new \App\Helpers\Helper)->formatNumber($totalAmount)."JOD" ?? '............................'}}</span>
        </div>

        <div class="top-section width-150 flex-gap-10 float-left">
            <div class="signature-box margin-bottom-30">
                <span>Second party signature</span>
            </div>
            <span>...........................</span>
        </div>

        <div class="text-center">
            <div class="date-text">
                <span>Date: <?php echo date('d/m/Y',strtotime($payment->created_at)); ?></span>
            </div>
        </div>
    </div>
</body>

</html>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عقد توكيل</title>
    <style>
        body {
            line-height: 1;
            direction: rtl;
            text-align: right;
            font-family: 'cairo', sans-serif;
        }

        .font-size-14{
            font-size:14px !important;
        }

        .font-wieght-bold{
            font-weight: bold !important;
        }

        .header {
            display: flex;
            align-items: flex-start;
            margin-bottom: 10px;
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
            line-height: 1.1;
            margin-bottom: 5px;
            clear: both;
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
            text-align: right;
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
            margin-bottom: 2px;
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
            padding: 10px;
        }

        .logo-img {
            height: 60px;
            /* Adjust the size based on your image */
            margin: 0 10px;
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
            margin-bottom: 20px;
        }

        .p-small {
            font-size: 1em;
            margin: 0;
        }

        .justify-text {
            margin: 10px 0;
            line-height: 1;
            font-size: 1em;
            text-align: justify;
            color: black;
        }

        .margin-top-10 {
            margin-top: 10px;
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
            <td class="text-right no-border">
                <img class="logo-large" src="{{ public_path('img/logo.png') }}" alt="Logo 4">
            </td>
            <td class="text-left no-border">
                <img class="logo-small" src="{{ public_path('img/qatra.png') }}" alt="Logo 2">
                <img class="logo-medium mr-10" src="{{ public_path('img/basar.png') }}" alt="Logo 1">
            </td>
        </tr>
    </table>

    <div class="center-title">
        <p class="p-teal">عقد توكيل خدمات</p>
    </div>
    <div class="center-subtitle">
        <p class="p-small">رقم العقد: {{$payment->contract_id ?? ''}}</p>
    </div>

    <div class="justify-text">
        <span class="fw-bold color-teal font-size-14 font-wieght-bold">مقدمة:</span>
        <span class="font-size-14">
        بما أن شركة معاً للوساطة التجارية ذ.م.م (معاً للتنمية) تهدف، وفقاً لأغراضها النظامية، إلى تعزيز الوعي، وتنمية قدرات الأفراد والمؤسسات والهيئات، وتسويق الخدمات والمنتجات التي تعمل على في تحسين جودة الحياة، فقد تم إبرام هذا العقد وملاحقه بين الطرفين وفقاً لما يلي:
        </span>
    </div>
    <div class="section">
        <div class="margin-top-10 font-size-14">
            <span class="font-size-14" style="font-weight:bold;">
            تُنفذ شركة معاً للوساطة التجارية ذ.م.م (معاً للتنمية) حلولاً ومشاريع تنموية مُخصصة للأفراد والشركات والمؤسسات غير الربحية حصراً عبر عقود خدمات مُتفق عليها، مع التأكيد على أن الشركة لا تقبل أي تبرعات مالية أو عينية بأي شكل من الأشكال، تماشياً مع سياساتها المؤسسية والأنظمة المعمول بها.
            </span>
        </div>
    </div>
    <div class="section">
        <span class="green-text font-size-14 font-wieght-bold">الطرف الأول:</span>
        <div class="border margin-top-10 font-size-14">
            <span class="font-size-14">
                شركة معاً للوساطة التجارية ذ.م.م (معاً للتنمية)، وهي شركة ذات مسؤولية محدودة مسجلة لدى دائرة مراقبة الشركات في وزارة الصناعة والتجارة الأردنية تحت الرقم (26808) بتاريخ 8/3/2022، ويمثلها لغايات التوقيع على هذا العقد المدير العام بصفته المفوض بالتوقيع على هذا العقد (ويشار إليها لاحقاً بـ "الطرف الأول أو الشركة أو معاً للوساطة التجارية").
            </span>
        </div>
    </div>

    <div class="section">
        <span class="green-text font-size-14 font-wieght-bold">الطرف الثاني:</span>
        <span class="font-size-14">كما هو ظاهر في الصفحة الثانية.</span>
    </div>
    <div class="section">
        <span class="green-text font-size-14 font-wieght-bold">البند الاول: <span style="font-weight:bold; color:#000" class="font-size-14">موضوع العقد</span></span>
        <span class="font-size-14">
            <span class="green-text font-size-14">1.</span>
            يوافق الطرف الثاني على اعتبار الطرف الأول وسيطاً لوجستياً مكلفاً بإيجاد الشركاء والمقاولين والموردين المناسبين لتنفيذ المشروع المحدد في هذا العقد
            <span class="green-text font-size-14">2.</span>
            يقوم الطرف الثاني بموجب هذا العقد بتوكيل الطرف الأول لتنفيذ المشروع المذكور في البند الثاني، بما يشمل اختيار كافة عناصر التنفيذ بعناية ومتابعة تشغيله وفقاً للشروط المتفق عليها.
        </span>
    </div>

    <div class="section">
        <span class="green-text font-size-14 font-wieght-bold">البند الثاني: <span style="font-weight:bold; color:#000" class="font-size-14">تفاصيل المشروع</span></span>
        <span class="font-size-14">يتم تحديد تفاصيل المشروع كما هو موضح في الملحق رقم (1) من هذا العقد.</span>
    </div>

    <div class="section">
        <span class="green-text font-size-14 font-wieght-bold">البند الثالث: <span style="font-weight:bold; color:#000" class="font-size-14">التزامات الطرف الأول</span></span>
        <span class="font-size-14"> 
            <span class="green-text font-size-14">1.</span>
        اختيار الشركاء المناسبين والأكفاء وذوي الخبرة في بلد تنفيذ المشروع، لضمان جودة تقديم الخدمة وتنفيذها بكل سهولة ويسر التنفيذ.
            <span class="green-text font-size-14">2.</span>
            تحديد المواقع المناسبة وتجهيز المستلزمات والخامات والمعدات ووسائل النقل اللازمة والمساندة اللوجستية للتنفيذ.
            <span class="green-text font-size-14">3.</span>
            الالتزام بالمدة الزمنية المحددة في العقد، إلا في حال حدوث ظروف قاهرة أو أحداث خارجة عن إرادة الطرف الأول تحول دون الالتزام بالمواعيد المحددة.
            <span class="green-text font-size-14">4.</span>
            تقديم الوثائق والتقارير اللازمة للطرف الثاني لإثبات تقدم العمل وإنجاز مراحله وفقاً للوسائل التي يراها الطرف الأول مناسبة.
            <span class="green-text font-size-14">5.</span>
            اتخاذ جميع الإجراءات اللازمة لضمان جودة الخدمات المقدمة، على أن تقتصر مسؤوليات الطرف الأول على الالتزامات المنصوص عليها في هذا العقد، ولا يتحمل الطرف الأول أي التزامات أو مسؤوليات أخرى خارجة عن إرادته.
            <span class="green-text font-size-14">6.</span>
            سداد الالتزامات المالية المستحقة للشركاء وفقاً لمراحل تنفيذ المشروع وبعد اكتماله.
            </span>
    </div>

    <div class="section">
        <span class="green-text font-size-14 font-wieght-bold">البند الرابع: <span style="font-weight:bold; color:#000" class="font-size-14">التزامات الطرف الثاني</span></span>
        <span class="font-size-14">
            <span class="green-text font-size-14">1.</span> 
            يتعهد الطرف الثاني بسداد المبلغ المحدد في الملحق رقم (1) أو ما يعادله بالعملة المحلية للطرف الأول، مقابل تنفيذ الخدمات المتفق عليها في هذا العقد.
            <span class="green-text font-size-14">2.</span> 
            يتحمل الطرف الأول بموجب الكفالة المقدمة من منفذ المشروع كفالة المشاريع الإنشائية فقط لمدة عام واحد من تاريخ التسليم، دون أي مسؤولية عن الظروف الخارجة عن إرادته.
        </span>
    </div>

    <div class="section">
        <span class="green-text font-size-14 font-wieght-bold">البند الخامس: <span style="font-weight:bold; color:#000" class="font-size-14">شروط عامة</span></span>
        <span class="font-size-14"> 
            <span class="green-text font-size-14">1.</span>
            إذا تعذر على الطرف الأول تنفيذ المشروع بعد التعاقد بسبب عدم توفر الظروف الملائمة أو وقوع قوة قاهرة، يتوجب على الشركة البحث عن حلول بديلة وإبلاغ الطرف الثاني بالمدة الزمنية الإضافية المطلوبة.
            <span class="green-text font-size-14">2.</span>
            يعتبر الطرف الأول قد أنجز جميع التزاماته بمجرد تنفيذ المشروع وتسليم تقرير مصور ووثائق التنفيذ، إلا في حال وجود ظروف قاهرة تمنع ذلك (كالحروب أو الأزمات الإنسانية). يتم الإخطار بانتهاء التنفيذ عبر رسالة واتساب على الرقم المحدد من الطرف الثاني أو بأي وسيلة يراها الطرف الأول مناسبة.
            <span class="green-text font-size-14"> 3.</span>
            تقتصر كفالة المشاريع الإنشائية على عام واحد من تاريخ التسليم، ويعتمد العمر الافتراضي لهذه المشاريع على ظروف الاستخدام من قبل المستفيدين النهائيين وفقاً للسياسات المعتمدة من الجهات المنفذة.
            <span class="green-text font-size-14">4.</span>
            يتم تنفيذ المشروع من قبل الشركة أو من خلال شركاتها الدولية أو المحلية المتخصصة في الدولة التي يتم فيها التنفيذ.
            <span class="green-text font-size-14">5.</span>
            في حال إلغاء الطرف الثاني للعقد قبل بدء التنفيذ، يتم خصم 25% من قيمة العقد، وإعادة المبلغ المتبقي. أما إذا بدأ التنفيذ، فلا يحق للطرف الثاني المطالبة باسترداد أي مبالغ مدفوعة، وتحدد الشركة وحدها ذلك.
            <span class="green-text font-size-14">6.</span>
            في حال نشوء أي خلاف بين الطرفين حول تنفيذ أو تفسير هذا العقد، يتم اللجوء إلى المحاكم الأردنية - محكمة قصر العدل (العبدلي)، ويخضع العقد للقوانين السارية في المملكة الأردنية الهاشمية.
            <span class="green-text font-size-14">7.</span>
            في حال تعذر على الشركة تسليم المواد المشتراة أو تأجيل توزيعها بسبب ظروف قاهرة أو أوضاع خارجة عن إرادتها (مثل الأزمات، الحروب، القيود القانونية، أو اشتراطات الجهات المنفذة)، يحق للشركة، ودون الحاجة للحصول على موافقة مسبقة من الطرف الثاني، إعادة توزيع المواد على الفئات المستهدفة أو الفئات المحتاجة الأخرى داخل و/أو خارج بلد التنفيذ، وذلك تفاديًا لتلف المواد أو ضياعها، ودون أن يترتب على الشركة أي مسؤولية بهذا الخصوص.
        </span>
    </div>
    <br><br>
    <table class="no-border-table">
        <tr>
            <td class="text-right no-border">
                <div class="inline-block-center">
                    <!-- Signature Box -->
                    <div class="signature-box-inline">
                        توقيع الطرف الأول
                    </div>
                    <!-- Name and Position -->
                    <div class="name-position">
                        <span>رامي ابو السمن</span><br>
                        <span>المدير العام</span>
                    </div>
                    <div>
                        <img width="90" height="70" src="{{ public_path('img/sign.png') }}" alt="sign" style="vertical-align: middle;" />
                        <img width="160" src="{{ public_path('img/new-sign.png') }}" alt="sign2" style="vertical-align: middle;" />
                    </div>
                </div>
            </td>
            <td class="text-left no-border">
                <div class="follow-us-container">
                    <p class="follow-us-title">تابع أعمالنا</p>
                    <div class="inline-block-center">
                        <img class="qr-code-img" src="{{ public_path('img/our_work.jpg') }}" alt="QR Code 1">
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <div class="section">
        <div class="contract-title">
            <p>مُلحق 1 / المشاريع</p>
        </div>
        <div class="section margin-bottom-25">
            <span class="green-text">الطرف الثاني :</span>
            <div class="border">
                <span class="padding-10" style="font-size:14px;">السيد/ة ،{{$payment->userDetails->first_name ?? ''}}
                    {{$payment->userDetails->last_name ?? ''}}  دولة الإقامة 
                    @if($country && !empty($country["country_name_arabic"]))
                        {{ $country["country_name_arabic"] }}
                    @else 
                        -------
                    @endif
                </span>
            </div>
        </div>
        <table class="no-border-table" border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>اسم المشروع</th> <!-- Project Name -->
                    <th>نوع المشروع</th> <!-- Project Type -->
                    <th>تفاصيل المشروع الموكل</th> <!-- Project Description -->
                    <th>دولة التنفيذ</th> <!-- Project Country -->
                    <th>نوع الدفع</th> <!-- Payment Type -->
                    <th>العدد</th> <!-- Quantity -->
                    <th>المبلغ</th> <!-- Amount -->
                    <th>أسماء الإهداء</th> <!-- Dedications -->
                </tr>
            </thead>
            <tbody>
                @php
                    $totalAmount = 0; // Initialize total amount variable
                @endphp
                @if (isset($carts))
                            @foreach ($carts as $cart)
                                        <tr>
                                            <td>{{ $cart->title ?? 'غير موجود' }}</td>
                                            <!-- Project Name -->
                                            <td>{{ $cart->model?->category?->getLocalizationTitle() ?? 'غير محدد' }}
                                            </td>
                                            <!-- Project Type -->
                                            <td>
                                                @php
                                                    $singlePrice = $cart->model?->single_price ?? null;
                                                    $qtyDisplay = ($singlePrice != null && $cart->price > 0)
                                                        ? (new \App\Helpers\Helper)->formatNumber((new \App\Helpers\Helper)->getSinglePriceQty($singlePrice, $cart->price))
                                                        : null;
                                                @endphp
                                                {{ $cart->description ?? 'لا يوجد وصف' }}
                                                @if($qtyDisplay !== null) 
                                                 - 
                                                لقد وكلتنا بشراء من هذا المنتج عدد ({{ $qtyDisplay }}) وتوريده بالإعتماد على عقد التوكيل الجماعي.
                                                @endif
                                            </td>
                                            <!-- Project Description -->
                                            <td>
                                                {{ $cart->location ?? 'غير معروف' }}
                                            </td>
                                            <td>{{ $cart->type == 'one_time' ? 'دفعة واحدة' : 'اشتراك' }}</td>
                                            <td>{{ $cart->quantity }}</td>
                                            <td>{{ (new \App\Helpers\Helper)->formatNumber($cart->price / $cart->quantity) }}د.أ</td>
                                            <td>
                                                @php
                                                    $dedicationsDisplay = $cart->dedications->map(function ($d) {
                                                        $phrase = $d->dedication_phrase_ar;
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
                    <td colspan="7" class="text-right fw-bold">المجموع</td> <!-- Total Amount Label -->
                    <td class="fw-bold">{{ (new \App\Helpers\Helper)->formatNumber($totalAmount) }}د.أ</td>
                    <!-- Total Amount -->
                </tr>
            </tbody>
        </table>
    </div>

    <div class="flex-between-center">
        <div class="top-section width-200 flex-gap-10 float-right">
            <div class="signature-box margin-bottom-30">
                <span class="font-size-14">اجمالي المبلغ المتعاقد عليه بالدينار</span>
            </div>
            <span>{{(new \App\Helpers\Helper)->formatNumber($totalAmount)."د.أ" ?? '............................'}}</span>
        </div>

        <div class="top-section width-150 flex-gap-10 float-left">
            <div class="signature-box margin-bottom-30">
                <span class="font-size-14"> توقيع الطرف الثاني</span>
            </div>
            <span>...........................</span>
        </div>
        <div class="text-center">
            <div class="date-text">
                <span>التاريخ :&nbsp;&nbsp;<?php echo date('d/m/Y', strtotime($payment->created_at)); ?></span>
            </div>
        </div>
    </div>

</body>

</html>

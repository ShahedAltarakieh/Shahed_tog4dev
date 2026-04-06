{{-- Arabic Email Footer Component --}}
{{-- Usage: @include('includes.email-footer-ar') --}}
<div class="footer" style="background-color: #0f7969; color: #fff; padding: 30px; text-align: center;">
    <img src="{{ asset('img/logo-white.png') }}" alt="الشعار" style="width: 200px; height: auto;">
    
    {{-- Social Media Links --}}
    <p class="social-links" style="font-size: 14px; margin-top: 15px; margin-bottom: 20px;">
        <a href="https://www.facebook.com/share/h4FZ2p8TsLWE2vKy/?mibextid=LQQJ4d" style="color: #fff; margin: 0 5px;">
            <img src="{{ asset('img/facebook.png') }}" alt="فيسبوك">
        </a>
        <a href="https://x.com/Tog4Dev?t=YxqzngaJv1WOAeS1241qMw&s=09" style="color: #fff; margin: 0 5px;">
            <img src="{{ asset('img/twitter.png') }}" alt="تويتر">
        </a>
        <a href="https://www.instagram.com/tog4dev/profilecard/?igsh=MmEyNnFhZDR2cDY4" style="color: #fff; margin: 0 5px;">
            <img src="{{ asset('img/instagram.png') }}" alt="انستغرام">
        </a>
        <a href="https://wa.link/czaarf" style="color: #fff; margin: 0 5px;">
            <img src="{{ asset('img/whatsapp2.png') }}" width="20" height="20" alt="واتساب">
        </a>
    </p>

    {{-- Policy Links Section --}}
    <div class="policy-links" style="margin: 20px 0; padding: 5px 0; border-top: 1px solid rgba(255,255,255,0.2); border-bottom: 1px solid rgba(255,255,255,0.2);">
        <p style="font-size: 14px; margin-bottom: 5px; color: #fff;">
            <a href="https://tog4dev.com/ar/الشروط-والاحكام" style="color: #fff; text-decoration: underline;">الشروط والاحكام</a> | 
            <a href="https://tog4dev.com/ar/سياسة-الإرجاع" style="color: #fff; text-decoration: underline;">سياسة الإستراجاع</a> | 
            <a href="https://tog4dev.com/ar/سياسة-الخصوصية" style="color: #fff; text-decoration: underline;">سياسية الخصوصية</a> | 
            <a href="https://tog4dev.com/ar/سياسة-الاشتراكات" style="color: #fff; text-decoration: underline;">سياسة الاشتراكات</a>
        </p>
        <p style="font-size: 14px; margin-bottom: 5px; color: #fff;">
            <a href="https://tog4dev.com/ar/من-نحن" style="color: #fff; text-decoration: underline;">تعرف على شركتنا وعقودنا</a>
        </p>
    </div>

    {{-- Copyright and Unsubscribe --}}
    <p style="font-size: 12px; color: #fff; margin: 10px 0;">© 2025 شركة معاً للوساطة التجارية (معاً للتنمية). جميع الحقوق محفوظة.</p>
    <p style="font-size: 12px; color: #fff; margin: 5px 0;">
        إذا كنت لا ترغب في تلقي هذه الرسائل الإلكترونية، يمكنك <a href="#" style="color: #fcca00;">إلغاء الاشتراك هنا</a>.
    </p>
</div> 
{{-- English Email Footer Component --}}
{{-- Usage: @include('includes.email-footer-en') --}}
<div class="footer" style="background-color: #0f7969; color: #fff; padding: 30px; text-align: center;">
    <img src="{{ asset('img/logo-white.png') }}" alt="Logo" style="width: 200px; height: auto;">
    
    {{-- Social Media Links --}}
    <p class="social-links" style="font-size: 14px; margin-top: 15px; margin-bottom: 20px;">
        <a href="https://www.facebook.com/share/h4FZ2p8TsLWE2vKy/?mibextid=LQQJ4d" style="color: #fff; margin: 0 5px;">
            <img src="{{ asset('img/facebook.png') }}" alt="Facebook">
        </a>
        <a href="https://x.com/Tog4Dev?t=YxqzngaJv1WOAeS1241qMw&s=09" style="color: #fff; margin: 0 5px;">
            <img src="{{ asset('img/twitter.png') }}" alt="Twitter">
        </a>
        <a href="https://www.instagram.com/tog4dev/profilecard/?igsh=MmEyNnFhZDR2cDY4" style="color: #fff; margin: 0 5px;">
            <img src="{{ asset('img/instagram.png') }}" alt="Instagram">
        </a>
        <a href="https://wa.link/czaarf" style="color: #fff; margin: 0 5px;">
            <img src="{{ asset('img/whatsapp2.png') }}" width="20" height="20" alt="whatsapp">
        </a>
    </p>

    {{-- Policy Links Section --}}
    <div class="policy-links" style="margin: 20px 0; padding: 15px 0; border-top: 1px solid rgba(255,255,255,0.2); border-bottom: 1px solid rgba(255,255,255,0.2);">
        <p style="font-size: 14px; margin-bottom: 5px; color: #fff;">
            <a href="https://tog4dev.com/en/terms-and-conditions" style="color: #fff; text-decoration: underline;">Terms and conditions</a> | 
            <a href="https://tog4dev.com/en/refund-policy" style="color: #fff; text-decoration: underline;">Refund Policy</a> | 
            <a href="https://tog4dev.com/en/privacy-policy" style="color: #fff; text-decoration: underline;">Privacy Policy</a> | 
            <a href="https://tog4dev.com/en/subscription-policy" style="color: #fff; text-decoration: underline;">Subscription Policy</a>
        </p>
        <p style="font-size: 14px; margin-bottom: 5px; color: #fff;">
            <a href="https://tog4dev.com/en/about-us" style="color: #fff; text-decoration: underline;">Learn about our company and contracts</a>
        </p>
    </div>

    {{-- Copyright and Unsubscribe --}}
    <p style="font-size: 12px; color: #fff; margin: 10px 0;">© 2025 Together for Intermediation Services (Together for Development). All rights reserved.</p>
    <p style="font-size: 12px; color: #fff; margin: 5px 0;">
        If you no longer wish to receive these emails, you can <a href="#" style="color: #fcca00;">unsubscribe here</a>.
    </p>
</div> 
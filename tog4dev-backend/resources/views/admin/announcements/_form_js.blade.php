<script>
(function () {
    var badgeColors = { LIVE: '#ef4444', INFO: '#3b82f6', ALERT: '#f59e0b', NEW: '#10b981' };

    var sourceSelect = document.getElementById('source_type');
    var newsSelect   = document.getElementById('news_id');
    var newsSection  = document.getElementById('news-link-section');
    var titleEn      = document.getElementById('ann-title-en');
    var titleAr      = document.getElementById('ann-title-ar');
    var textEn       = document.getElementById('ann-text-en');
    var textAr       = document.getElementById('ann-text-ar');
    var ctaEn        = document.querySelector('[name="cta_text"]');
    var ctaAr        = document.querySelector('[name="cta_text_ar"]');
    var linkField    = document.getElementById('ann-link');
    var badgeSelect  = document.getElementById('badge_type');

    var previewLang  = 'en';

    function toggleSourceType() {
        var isNews = sourceSelect.value === 'news';
        newsSection.style.display = isNews ? '' : 'none';
        if (!isNews) { newsSelect.value = ''; }
    }
    sourceSelect.addEventListener('change', toggleSourceType);
    toggleSourceType();

    newsSelect.addEventListener('change', function () {
        var opt = this.options[this.selectedIndex];
        if (this.value) {
            if (opt.dataset.titleEn)   titleEn.value = opt.dataset.titleEn;
            if (opt.dataset.titleAr)   titleAr.value = opt.dataset.titleAr;
            if (opt.dataset.excerptEn) textEn.value  = opt.dataset.excerptEn;
            if (opt.dataset.excerptAr) textAr.value  = opt.dataset.excerptAr;
            var slugEn = opt.dataset.slugEn || '';
            var slugAr = opt.dataset.slugAr || '';
            linkField.value = slugEn ? '/en/news/' + slugEn : (slugAr ? '/ar/news/' + slugAr : '');
            updatePreview();
        }
    });

    function updatePreview() {
        var badge = badgeSelect.value;
        var en = (textEn.value || '').trim();
        var ar = (textAr.value || '').trim();
        var ctaE = (ctaEn.value || '').trim();
        var ctaA = (ctaAr.value || '').trim();

        var displayText, displayCta;
        if (previewLang === 'ar') {
            displayText = ar || en || '{{ __("app.preview_sample_text") }}';
            displayCta  = ctaA || ctaE;
        } else {
            displayText = en || ar || '{{ __("app.preview_sample_text") }}';
            displayCta  = ctaE || ctaA;
        }

        var preview = document.getElementById('live-preview');
        preview.setAttribute('dir', previewLang === 'ar' ? 'rtl' : 'ltr');

        document.getElementById('preview-badge').textContent = badge;
        document.getElementById('preview-badge').style.background = badgeColors[badge] || '#3b82f6';

        var textEl = document.getElementById('preview-text');
        textEl.textContent = displayText.length > 100 ? displayText.substring(0, 100) + '...' : displayText;

        var ctaEl = document.getElementById('preview-cta');
        if (displayCta) {
            ctaEl.textContent = displayCta + (previewLang === 'ar' ? ' ←' : ' →');
            ctaEl.style.display = '';
        } else {
            ctaEl.style.display = 'none';
        }
    }

    [textEn, textAr, ctaEn, ctaAr, badgeSelect].forEach(function (el) {
        if (el) el.addEventListener('input', updatePreview);
        if (el && el.tagName === 'SELECT') el.addEventListener('change', updatePreview);
    });

    document.querySelectorAll('.preview-lang').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.preview-lang').forEach(function (b) { b.classList.remove('active'); });
            this.classList.add('active');
            previewLang = this.dataset.lang;
            updatePreview();
        });
    });

    document.querySelectorAll('.preview-device').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.preview-device').forEach(function (b) { b.classList.remove('active'); });
            this.classList.add('active');
            var preview = document.getElementById('live-preview');
            if (this.dataset.mode === 'mobile') {
                preview.style.maxWidth = '375px';
                preview.style.margin = '0 auto';
                preview.style.fontSize = '12px';
            } else {
                preview.style.maxWidth = '';
                preview.style.margin = '';
                preview.style.fontSize = '14px';
            }
        });
    });

    updatePreview();
})();
</script>

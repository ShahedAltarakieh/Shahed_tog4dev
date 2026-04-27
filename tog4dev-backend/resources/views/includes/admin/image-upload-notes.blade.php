<div class="upload-guidelines mt-1" style="background: #f0f7ff; border: 1px solid #d0e3f7; border-radius: 6px; padding: 8px 12px; font-size: 12px; color: #4a6785;">
    <div style="display: flex; align-items: flex-start; gap: 6px;">
        <i class="fas fa-info-circle" style="color: #3b82f6; margin-top: 2px;"></i>
        <div>
            <div style="font-weight: 600; margin-bottom: 2px;">{{ __('app.image guidelines') ?? 'Image Guidelines' }}</div>
            <div><strong>{{ __('app.recommended size') ?? 'Recommended Size' }}:</strong> {{ $recommendedSize ?? '1200 x 800 px' }}</div>
            <div><strong>{{ __('app.max file size') ?? 'Max File Size' }}:</strong> {{ $maxSize ?? '2 MB' }}</div>
            <div><strong>{{ __('app.allowed extensions') ?? 'Allowed Extensions' }}:</strong>
                <span style="display: inline-flex; gap: 4px; flex-wrap: wrap; margin-top: 2px;">
                    @foreach(explode(',', $extensions ?? 'png,jpg,jpeg,webp') as $ext)
                        <span style="background: #e2e8f0; color: #475569; padding: 1px 6px; border-radius: 3px; font-size: 11px; font-weight: 500;">.{{ trim($ext) }}</span>
                    @endforeach
                </span>
            </div>
        </div>
    </div>
</div>

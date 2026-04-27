<div class="upload-guidelines mt-2" style="background: linear-gradient(135deg, #f0f7ff 0%, #e8f4fd 100%); border: 1.5px solid #c8ddf0; border-radius: 10px; padding: 12px 16px; font-size: 13px; color: #3a5f82;">
    <div style="display: flex; align-items: flex-start; gap: 10px;">
        <div style="width: 28px; height: 28px; border-radius: 50%; background: rgba(59, 130, 246, 0.12); display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px;">
            <i class="fas fa-info-circle" style="color: #3b82f6; font-size: 13px;"></i>
        </div>
        <div style="flex: 1;">
            <div style="font-weight: 700; margin-bottom: 6px; font-size: 13px; color: #1e3a5f;">{{ __('app.image guidelines') }}</div>
            <div style="display: flex; flex-direction: column; gap: 4px;">
                <div style="display: flex; align-items: center; gap: 6px;">
                    <i class="fas fa-expand-arrows-alt" style="color: #6b8db5; font-size: 11px; width: 14px; text-align: center;"></i>
                    <span><strong style="color: #2d5480;">{{ __('app.recommended size') }}:</strong> {{ $recommendedSize ?? '1200 x 800 px' }}</span>
                </div>
                <div style="display: flex; align-items: center; gap: 6px;">
                    <i class="fas fa-weight-hanging" style="color: #6b8db5; font-size: 11px; width: 14px; text-align: center;"></i>
                    <span><strong style="color: #2d5480;">{{ __('app.max file size') }}:</strong> {{ $maxSize ?? '5 MB' }}</span>
                </div>
                <div style="display: flex; align-items: center; gap: 6px;">
                    <i class="fas fa-file-image" style="color: #6b8db5; font-size: 11px; width: 14px; text-align: center;"></i>
                    <span><strong style="color: #2d5480;">{{ __('app.allowed extensions') }}:</strong></span>
                    <span style="display: inline-flex; gap: 4px; flex-wrap: wrap;">
                        @foreach(explode(',', $extensions ?? 'png,jpg,jpeg,webp') as $ext)
                            <span style="background: linear-gradient(135deg, #e2e8f0, #dce4ed); color: #374151; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; border: 1px solid #c8d5e0;">.{{ trim($ext) }}</span>
                        @endforeach
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

namespace Tests\Feature;

use App\Models\Language;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LanguageInvariantsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Language::query()->delete();
        Language::create(['code' => 'en', 'name' => 'English', 'native_name' => 'English', 'direction' => 'ltr', 'is_default' => true,  'is_active' => true,  'position' => 1]);
        Language::create(['code' => 'ar', 'name' => 'Arabic',  'native_name' => 'العربية', 'direction' => 'rtl', 'is_default' => false, 'is_active' => true,  'position' => 2]);
    }

    public function test_exactly_one_default_after_set_default(): void
    {
        $ar = Language::where('code', 'ar')->first();
        $ar->is_default = true;
        $ar->save();

        $this->assertSame(1, Language::where('is_default', true)->count());
        $this->assertSame('ar', Language::defaultCode());
    }

    public function test_default_language_cannot_be_deactivated_via_save(): void
    {
        $en = Language::where('code', 'en')->first();
        $en->is_active = false;
        $en->save();

        // Saving model invariant forces default rows to remain active.
        $en->refresh();
        $this->assertTrue($en->is_active);
    }

    public function test_default_code_self_heals_when_no_row_marked_default(): void
    {
        // Force a degenerate state (bypassing model events).
        Language::query()->update(['is_default' => false]);
        $this->assertSame(0, Language::where('is_default', true)->count());

        $code = Language::defaultCode();

        $this->assertNotEmpty($code);
        $this->assertSame(1, Language::where('is_default', true)->count());
    }

    public function test_unsetting_only_default_throws(): void
    {
        $en = Language::where('code', 'en')->first();
        $en->is_default = false;

        $this->expectException(\RuntimeException::class);
        $en->save();
    }
}

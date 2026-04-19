<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AboutPage;
use App\Models\AboutSection;
use App\Models\AboutSectionItem;

class AboutPageContentSeeder extends Seeder
{
    public function run(): void
    {
        $page = AboutPage::where('country_code', 'JO')->first();
        if (!$page) {
            $page = AboutPage::create([
                'country_code' => 'JO',
                'status' => 'draft',
                'version' => 1,
            ]);
        }

        $page->update([
            'meta_title'       => 'من نحن | معاً للتنمية',
            'meta_title_en'    => 'About Us | Together For Development',
            'meta_description' => 'منصة أردنية تجمع المتبرعين والمؤسسات والمستفيدين بشفافية وأثر مستدام.',
            'meta_description_en' => 'A Jordanian platform connecting donors, organizations, and beneficiaries with transparency and sustainable impact.',
            'status' => 'published',
            'version' => max(1, (int)$page->version),
        ]);

        $sections = [
            'hero' => [
                'title' => 'من نحن؟',
                'title_en' => 'Who We Are?',
                'subtitle' => 'معاً للتنمية',
                'subtitle_en' => 'Together For Development',
            ],
            'intro' => [
                'title' => 'رحلتنا',
                'title_en' => 'Our Journey',
                'body' => '<p>نحن منصة أردنية خيرية تأسست لتمكين المجتمعات وتعزيز العمل الخيري عبر التكنولوجيا. نسعى لتقديم حلول مبتكرة تربط المتبرعين بمن يحتاجون إليهم بشفافية وكفاءة.</p>',
                'body_en' => '<p>We are a Jordanian charitable platform founded to empower communities and enhance philanthropy through technology. We strive to deliver innovative solutions that transparently and efficiently connect donors with those in need.</p>',
                'image' => 'about/sections/journey-team.png',
            ],
            'highlights' => [
                'title' => 'لماذا نتميز',
                'title_en' => 'Why We Stand Out',
                'items' => [
                    ['title'=>'الشفافية','title_en'=>'Transparency','description'=>'نلتزم بأعلى معايير الشفافية في جميع عملياتنا','description_en'=>'We adhere to the highest standards of transparency in all our operations','icon'=>'fas fa-shield-alt'],
                    ['title'=>'الابتكار','title_en'=>'Innovation','description'=>'نوظّف أحدث التقنيات لتطوير تجربة التبرع','description_en'=>'We use the latest technologies to improve the donation experience','icon'=>'fas fa-lightbulb'],
                    ['title'=>'الاستدامة','title_en'=>'Sustainability','description'=>'نبني مشاريع مستدامة تحقق أثراً طويل الأمد','description_en'=>'We build sustainable projects that achieve long-term impact','icon'=>'fas fa-seedling'],
                ],
            ],
            'statement' => [
                'title' => 'بياننا',
                'title_en' => 'Our Statement',
                'body' => '<p>نؤمن أن العطاء قوة تُغيّر حياة الناس، وأن التعاون بين الأفراد والمؤسسات هو الطريق نحو مجتمعٍ أكثر عدلاً وكرامةً واستقراراً.</p>',
                'body_en' => '<p>We believe giving is a force that changes lives, and that collaboration between individuals and institutions is the path to a fairer, more dignified, and more stable society.</p>',
            ],
            'visionMission' => [
                'title' => 'رؤيتنا ورسالتنا',
                'title_en' => 'Vision & Mission',
                'items' => [
                    ['title'=>'رؤيتنا','title_en'=>'Our Vision','description'=>'أن نكون المنصة الرائدة للعمل الخيري الرقمي في الأردن والمنطقة، نُمكّن العطاء ونصنع أثراً ملموساً.','description_en'=>'To be the leading digital philanthropy platform in Jordan and the region — empowering giving and creating tangible impact.','icon'=>'fas fa-eye'],
                    ['title'=>'رسالتنا','title_en'=>'Our Mission','description'=>'تمكين الأفراد والمؤسسات من إحداث تغيير حقيقي عبر أدوات تبرع شفافة وذكية ومستدامة.','description_en'=>'To enable individuals and organizations to create real change through transparent, smart, and sustainable donation tools.','icon'=>'fas fa-bullseye'],
                ],
            ],
            'coreValues' => [
                'title' => 'قيمنا الجوهرية',
                'title_en' => 'Our Core Values',
                'items' => [
                    ['title'=>'النزاهة','title_en'=>'Integrity','description'=>'نعمل بأمانة ومسؤولية في كل قرار وكل تبرّع.','description_en'=>'We work with honesty and accountability in every decision and every donation.','icon'=>'fas fa-balance-scale'],
                    ['title'=>'التعاطف','title_en'=>'Empathy','description'=>'نضع الإنسان في قلب عملنا ونحترم كرامته.','description_en'=>'We put people at the heart of our work and honor their dignity.','icon'=>'fas fa-heart'],
                    ['title'=>'التعاون','title_en'=>'Collaboration','description'=>'نؤمن أن الأثر الحقيقي يُصنع معاً.','description_en'=>'We believe real impact is made together.','icon'=>'fas fa-hands-helping'],
                    ['title'=>'الإبداع','title_en'=>'Creativity','description'=>'نبتكر حلولاً ذكية لتحديات حقيقية.','description_en'=>'We design smart solutions for real challenges.','icon'=>'fas fa-rocket'],
                ],
            ],
            'founders' => [
                'title' => 'فريق المؤسسين',
                'title_en' => 'Our Founders',
                'items' => [
                    ['title'=>'أحمد الخطيب','title_en'=>'Ahmad Al-Khatib','label'=>'الرئيس التنفيذي والمؤسس','label_en'=>'CEO & Co-Founder','description'=>'يقود رؤية المنصة بخبرة تزيد عن 15 عاماً في القطاع التنموي والتقني.','description_en'=>'Leads the platform’s vision with 15+ years of experience in development and tech sectors.','image'=>'about/items/founder-1.png','social_links'=>['linkedin'=>'https://linkedin.com/','twitter'=>'https://twitter.com/']],
                    ['title'=>'ليلى العلي','title_en'=>'Layla Al-Ali','label'=>'مديرة الشراكات والمؤسسة المشاركة','label_en'=>'Head of Partnerships & Co-Founder','description'=>'تبني شراكات استراتيجية مع المؤسسات الخيرية والقطاع الخاص في الأردن والمنطقة.','description_en'=>'Builds strategic partnerships with charities and private-sector partners across Jordan and the region.','image'=>'about/items/founder-2.png','social_links'=>['linkedin'=>'https://linkedin.com/']],
                    ['title'=>'عمر منصور','title_en'=>'Omar Mansour','label'=>'مدير التقنية والمؤسس المشارك','label_en'=>'CTO & Co-Founder','description'=>'يقود تطوير المنصة وضمان أعلى معايير الأمان والشفافية في كل تبرّع.','description_en'=>'Leads platform engineering, ensuring the highest standards of security and transparency in every donation.','image'=>'about/items/founder-3.png','social_links'=>['linkedin'=>'https://linkedin.com/','github'=>'https://github.com/']],
                ],
            ],
            'beliefs' => [
                'title' => 'ما نؤمن به',
                'title_en' => 'What We Believe',
                'items' => [
                    ['title'=>'العطاء حقّ ومسؤولية','title_en'=>'Giving Is a Right & a Responsibility','description'=>'كل إنسان قادر على أن يُحدث فرقاً، مهما كان حجم مساهمته.','description_en'=>'Everyone can make a difference — no matter how small the contribution.','icon'=>'fas fa-hand-holding-heart'],
                    ['title'=>'الشفافية تبني الثقة','title_en'=>'Transparency Builds Trust','description'=>'نُظهر أين يذهب كل دينار وكيف يُحدث أثره.','description_en'=>'We show where every dinar goes and how it makes its impact.','icon'=>'fas fa-search-dollar'],
                    ['title'=>'التكنولوجيا في خدمة الإنسان','title_en'=>'Technology Serves People','description'=>'نسخّر الأدوات الرقمية لتقريب المتبرع من المستفيد.','description_en'=>'We put digital tools to work to bring donors closer to beneficiaries.','icon'=>'fas fa-microchip'],
                    ['title'=>'الأثر يُقاس لا يُفترض','title_en'=>'Impact Is Measured, Not Assumed','description'=>'نقيس أثر مشاريعنا ونشاركه علناً مع مجتمعنا.','description_en'=>'We measure the impact of our projects and share it openly with our community.','icon'=>'fas fa-chart-line'],
                ],
            ],
            'stats' => [
                'title' => 'أثرنا بالأرقام',
                'title_en' => 'Our Impact in Numbers',
                'items' => [
                    ['value'=>'25000','label'=>'متبرع نشط','label_en'=>'Active Donors','icon'=>'fas fa-users'],
                    ['value'=>'180','label'=>'مشروع منفّذ','label_en'=>'Projects Delivered','icon'=>'fas fa-project-diagram'],
                    ['value'=>'45','label'=>'منظمة شريكة','label_en'=>'Partner Organizations','icon'=>'fas fa-handshake'],
                    ['value'=>'120000','label'=>'مستفيد مباشر','label_en'=>'Direct Beneficiaries','icon'=>'fas fa-heart'],
                ],
            ],
            'slogan' => [
                'title' => 'معاً، نصنع الأثر',
                'title_en' => 'Together, We Make Impact',
                'body' => 'انضم إلى آلاف المتبرعين الذين اختاروا أن يكونوا جزءاً من التغيير.',
                'body_en' => 'Join thousands of donors who chose to be part of the change.',
                'cta_text' => 'تبرّع الآن',
                'cta_text_en' => 'Donate Now',
                'cta_link' => '/donate',
                'cta_link_en' => '/donate',
            ],
            'contact' => [
                'title' => 'تواصل معنا',
                'title_en' => 'Contact Us',
                'body' => '<p>نسعد بسماع آرائكم واقتراحاتكم. تواصلوا معنا في أي وقت.</p>',
                'body_en' => '<p>We’d love to hear your thoughts and suggestions. Reach out anytime.</p>',
                'items' => [
                    ['title'=>'البريد الإلكتروني','title_en'=>'Email','value'=>'info@tog4dev.com','link'=>'mailto:info@tog4dev.com','icon'=>'fas fa-envelope'],
                    ['title'=>'الهاتف','title_en'=>'Phone','value'=>'+962 6 555 0123','link'=>'tel:+96265550123','icon'=>'fas fa-phone'],
                    ['title'=>'العنوان','title_en'=>'Address','value'=>'عمّان، الأردن','label_en'=>'Amman, Jordan','link'=>'https://maps.google.com/?q=Amman,Jordan','icon'=>'fas fa-map-marker-alt'],
                ],
            ],
            'partners' => [
                'title' => 'شركاؤنا',
                'title_en' => 'Our Partners',
                'items' => [
                    ['title'=>'وزارة التنمية الاجتماعية','title_en'=>'Ministry of Social Development','link'=>'#'],
                    ['title'=>'تكية أم علي','title_en'=>'Tkiyet Um Ali','link'=>'#'],
                    ['title'=>'مؤسسة الملك حسين','title_en'=>'King Hussein Foundation','link'=>'#'],
                    ['title'=>'مؤسسة الحسن للشباب','title_en'=>'Al-Hussein Foundation for Youth','link'=>'#'],
                    ['title'=>'الهلال الأحمر الأردني','title_en'=>'Jordan Red Crescent','link'=>'#'],
                    ['title'=>'مؤسسة نهر الأردن','title_en'=>'Jordan River Foundation','link'=>'#'],
                ],
            ],
        ];

        $order = 0;
        foreach ($sections as $key => $data) {
            $section = AboutSection::firstOrNew([
                'about_page_id' => $page->id,
                'section_key' => $key,
            ]);

            $section->title       = $data['title']       ?? null;
            $section->title_en    = $data['title_en']    ?? null;
            $section->subtitle    = $data['subtitle']    ?? null;
            $section->subtitle_en = $data['subtitle_en'] ?? null;
            $section->body        = $data['body']        ?? null;
            $section->body_en     = $data['body_en']     ?? null;
            $section->image       = $data['image']       ?? $section->image;
            $section->cta_text    = $data['cta_text']    ?? null;
            $section->cta_text_en = $data['cta_text_en'] ?? null;
            $section->cta_link    = $data['cta_link']    ?? null;
            $section->cta_link_en = $data['cta_link_en'] ?? null;
            $section->is_visible  = true;
            $section->sort_order  = $order++;
            $section->save();

            if (isset($data['items'])) {
                $section->items()->delete();
                $iOrder = 0;
                foreach ($data['items'] as $i) {
                    AboutSectionItem::create([
                        'about_section_id' => $section->id,
                        'title'            => $i['title']        ?? null,
                        'title_en'         => $i['title_en']     ?? null,
                        'description'      => $i['description']  ?? null,
                        'description_en'   => $i['description_en']?? null,
                        'image'            => $i['image']        ?? null,
                        'icon'             => $i['icon']         ?? null,
                        'link'             => $i['link']         ?? null,
                        'link_en'          => $i['link_en']      ?? null,
                        'value'            => $i['value']        ?? null,
                        'label'            => $i['label']        ?? null,
                        'label_en'         => $i['label_en']     ?? null,
                        'social_links'     => $i['social_links'] ?? null,
                        'sort_order'       => $iOrder++,
                        'is_visible'       => true,
                    ]);
                }
            }
        }

        $this->command->info('About Us page seeded with full content for ' . count($sections) . ' sections.');
    }
}

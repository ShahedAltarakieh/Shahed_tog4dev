<?php

namespace Database\Seeders;

use App\Models\AboutPage;
use App\Models\AboutSection;
use Illuminate\Database\Seeder;

class AboutPageSeeder extends Seeder
{
    public function run(): void
    {
        if (AboutPage::count() > 0) {
            $this->command->info('About pages already exist, skipping seed.');
            return;
        }

        $page = AboutPage::create([
            'country_code' => 'JO',
            'status' => 'published',
            'version' => 1,
            'meta_title' => 'من نحن - معاً للتنمية',
            'meta_title_en' => 'About Us - Together For Development',
            'meta_description' => 'نقدّم حلولًا ذكية تخدم الإنسان، وتُمكّن المؤسسات من تنفيذ مشاريع تنموية بجودة وشفافية واستدامة حول العالم',
            'meta_description_en' => 'Smart solutions for people and institutions to build impactful, transparent, and lasting development worldwide.',
            'published_at' => now(),
        ]);

        $sections = [
            [
                'key' => 'hero',
                'data' => [
                    'title' => 'من نحن؟',
                    'title_en' => 'Who We Are?',
                    'subtitle' => 'معاً للتنمية',
                    'subtitle_en' => 'Together For Development',
                ],
            ],
            [
                'key' => 'intro',
                'data' => [
                    'title' => 'رحلتنا',
                    'title_en' => 'Our Journey',
                    'body' => 'نحن منصة خيرية أردنية تأسست بهدف تمكين المجتمعات وتعزيز العمل الخيري عبر التكنولوجيا. نسعى لتقديم حلول مبتكرة تربط بين المتبرعين والمحتاجين بشفافية وكفاءة.',
                    'body_en' => 'We are a Jordanian charitable platform founded to empower communities and enhance philanthropy through technology. We strive to deliver innovative solutions that transparently and efficiently connect donors with those in need.',
                ],
            ],
            [
                'key' => 'highlights',
                'data' => [
                    'title' => 'لماذا نتميز؟',
                    'title_en' => 'Why We Stand Out',
                ],
                'items' => [
                    ['title' => 'الشفافية', 'title_en' => 'Transparency', 'description' => 'نلتزم بأعلى معايير الشفافية في جميع عملياتنا', 'description_en' => 'We adhere to the highest standards of transparency in all our operations', 'icon' => 'fas fa-eye'],
                    ['title' => 'الابتكار', 'title_en' => 'Innovation', 'description' => 'نستخدم أحدث التقنيات لتحسين تجربة التبرع', 'description_en' => 'We use the latest technologies to improve the donation experience', 'icon' => 'fas fa-lightbulb'],
                    ['title' => 'الاستدامة', 'title_en' => 'Sustainability', 'description' => 'نبني مشاريع مستدامة تحقق أثراً طويل الأمد', 'description_en' => 'We build sustainable projects that achieve long-term impact', 'icon' => 'fas fa-leaf'],
                ],
            ],
            [
                'key' => 'statement',
                'data' => [
                    'title' => 'رسالتنا',
                    'title_en' => 'Our Message',
                    'body' => 'نؤمن بأن التنمية الحقيقية تبدأ من التمكين. نعمل على بناء جسور بين المؤسسات والأفراد لتحقيق تنمية شاملة ومستدامة في الأردن والمنطقة.',
                    'body_en' => 'We believe true development starts with empowerment. We work to build bridges between institutions and individuals to achieve comprehensive and sustainable development in Jordan and the region.',
                ],
            ],
            [
                'key' => 'visionMission',
                'data' => [
                    'title' => 'الرؤية والرسالة',
                    'title_en' => 'Vision & Mission',
                ],
                'items' => [
                    ['title' => 'رؤيتنا', 'title_en' => 'Our Vision', 'description' => 'أن نكون المنصة الرائدة في العمل الخيري الرقمي في العالم العربي', 'description_en' => 'To be the leading platform in digital philanthropy in the Arab world', 'icon' => 'fas fa-binoculars'],
                    ['title' => 'رسالتنا', 'title_en' => 'Our Mission', 'description' => 'تمكين المجتمعات من خلال حلول تقنية مبتكرة تسهل العطاء وتعزز الشفافية', 'description_en' => 'Empowering communities through innovative technological solutions that facilitate giving and enhance transparency', 'icon' => 'fas fa-bullseye'],
                ],
            ],
            [
                'key' => 'coreValues',
                'data' => [
                    'title' => 'قيمنا الأساسية',
                    'title_en' => 'Our Core Values',
                    'subtitle' => 'المبادئ التي توجه عملنا',
                    'subtitle_en' => 'The principles that guide our work',
                ],
                'items' => [
                    ['title' => 'النزاهة', 'title_en' => 'Integrity', 'description' => 'نتحلى بأعلى معايير الأمانة والصدق', 'description_en' => 'We uphold the highest standards of honesty', 'icon' => 'fas fa-shield-alt'],
                    ['title' => 'التعاون', 'title_en' => 'Collaboration', 'description' => 'نعمل مع شركائنا لتحقيق أهداف مشتركة', 'description_en' => 'We work with partners to achieve shared goals', 'icon' => 'fas fa-handshake'],
                    ['title' => 'الإتقان', 'title_en' => 'Excellence', 'description' => 'نسعى للتميز في كل ما نقدمه', 'description_en' => 'We strive for excellence in everything we deliver', 'icon' => 'fas fa-star'],
                    ['title' => 'المسؤولية', 'title_en' => 'Responsibility', 'description' => 'نتحمل مسؤولية تأثيرنا على المجتمع', 'description_en' => 'We take responsibility for our impact on society', 'icon' => 'fas fa-heart'],
                ],
            ],
            [
                'key' => 'founders',
                'data' => [
                    'title' => 'فريق القيادة',
                    'title_en' => 'Leadership Team',
                ],
                'items' => [],
            ],
            [
                'key' => 'beliefs',
                'data' => [
                    'title' => 'ما نؤمن به',
                    'title_en' => 'What We Believe',
                ],
                'items' => [
                    ['title' => 'قوة التكنولوجيا', 'title_en' => 'Power of Technology', 'description' => 'التكنولوجيا أداة قوية لتحقيق الخير', 'description_en' => 'Technology is a powerful tool for doing good', 'icon' => 'fas fa-laptop-code'],
                    ['title' => 'الشمولية', 'title_en' => 'Inclusivity', 'description' => 'الجميع يستحق فرصة المساهمة والاستفادة', 'description_en' => 'Everyone deserves the chance to contribute and benefit', 'icon' => 'fas fa-users'],
                    ['title' => 'التأثير المستدام', 'title_en' => 'Lasting Impact', 'description' => 'نسعى لتحقيق تغيير حقيقي ودائم', 'description_en' => 'We seek real and lasting change', 'icon' => 'fas fa-seedling'],
                ],
            ],
            [
                'key' => 'stats',
                'data' => [
                    'title' => 'إنجازاتنا بالأرقام',
                    'title_en' => 'Our Achievements',
                ],
                'items' => [
                    ['value' => '+2,000,000', 'label' => 'مستفيد', 'label_en' => 'Beneficiaries', 'icon' => 'fas fa-users'],
                    ['value' => '+500', 'label' => 'مشروع', 'label_en' => 'Projects', 'icon' => 'fas fa-project-diagram'],
                    ['value' => '+50', 'label' => 'شريك', 'label_en' => 'Partners', 'icon' => 'fas fa-handshake'],
                    ['value' => '+10', 'label' => 'دول', 'label_en' => 'Countries', 'icon' => 'fas fa-globe-americas'],
                ],
            ],
            [
                'key' => 'slogan',
                'data' => [
                    'title' => 'معاً نصنع الفرق',
                    'title_en' => 'Together We Make a Difference',
                    'body' => 'انضم إلينا في رحلة التغيير الإيجابي',
                    'body_en' => 'Join us on a journey of positive change',
                    'cta_text' => 'تبرع الآن',
                    'cta_text_en' => 'Donate Now',
                    'cta_link' => '/donate',
                    'cta_link_en' => '/donate',
                ],
            ],
            [
                'key' => 'contact',
                'data' => [
                    'title' => 'تواصل معنا',
                    'title_en' => 'Contact Us',
                ],
                'items' => [
                    ['title' => 'البريد الإلكتروني', 'title_en' => 'Email', 'value' => 'info@tog4dev.com', 'link' => 'mailto:info@tog4dev.com', 'icon' => 'fas fa-envelope'],
                    ['title' => 'الهاتف', 'title_en' => 'Phone', 'value' => '+962-6-XXX-XXXX', 'link' => 'tel:+9626XXXXXXX', 'icon' => 'fas fa-phone'],
                    ['title' => 'الموقع', 'title_en' => 'Location', 'value' => 'عمان، الأردن', 'link' => '#', 'icon' => 'fas fa-map-marker-alt'],
                ],
            ],
            [
                'key' => 'partners',
                'data' => [
                    'title' => 'شركاؤنا',
                    'title_en' => 'Our Partners',
                ],
                'items' => [],
            ],
        ];

        foreach ($sections as $index => $sectionData) {
            $section = $page->sections()->create(array_merge([
                'section_key' => $sectionData['key'],
                'sort_order' => $index,
                'is_visible' => true,
            ], $sectionData['data']));

            if (!empty($sectionData['items'])) {
                foreach ($sectionData['items'] as $itemIndex => $itemData) {
                    $section->items()->create(array_merge($itemData, [
                        'sort_order' => $itemIndex,
                        'is_visible' => true,
                    ]));
                }
            }
        }

        $this->command->info('About Us page seeded successfully with ' . count($sections) . ' sections.');
    }
}

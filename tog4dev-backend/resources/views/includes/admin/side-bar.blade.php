<li class="sidebar-section-title">{{ __('app.main') }}</li>

<li>
    <a href="{{ route('dashboard') }}" data-tooltip="{{ __('app.dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active-menu' : '' }}">
        <i class="fas fa-th-large"></i>
        <span>{{ __('app.dashboard') }}</span>
    </a>
</li>
<li>
    <a href="{{ route('system.reports') }}" data-tooltip="{{ __('app.reports_analytics') }}" class="{{ request()->routeIs('system.reports') ? 'active-menu' : '' }}">
        <i class="fas fa-chart-bar"></i>
        <span>{{ __('app.reports_analytics') }}</span>
    </a>
</li>

<li class="sidebar-section-title">{{ __('app.content') }}</li>

<li>
    <a href="{{ route('sliders.index') }}" data-tooltip="{{ __('app.sliders') }}" class="{{ request()->routeIs('sliders.*') ? 'active-menu' : '' }}">
        <i class="fas fa-images"></i>
        <span>{{ __('app.sliders') }}</span>
    </a>
</li>
<li>
    <a href="{{ route('quick-contributions.index') }}" data-tooltip="{{ __('app.contributions') }}" class="{{ request()->routeIs('quick-contributions.*') ? 'active-menu' : '' }}">
        <i class="fas fa-hand-holding-heart"></i>
        <span>{{ __('app.contributions') }}</span>
    </a>
</li>
<li>
    <a href="#categories" data-tooltip="{{ __('app.categories') }}" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-folder-open"></i>
        <span>{{ __('app.categories') }}</span>
        <span class="menu-arrow"><i class="fas fa-chevron-down"></i></span>
    </a>
    <div class="collapse" id="categories">
        <ul class="nav-second-level">
            <li><a href="{{ route('categories.index', ["type" => "projects"]) }}"><span class="submenu-dot"></span> {{ __('app.projects') }}</a></li>
            <li><a href="{{ route('categories.index', ["type" => "organization"]) }}"><span class="submenu-dot"></span> {{ __('app.organization') }}</a></li>
            <li><a href="{{ route('categories.index', ["type" => "crowdfunding"]) }}"><span class="submenu-dot"></span> {{ __('app.crowdfunding') }}</a></li>
            <li><a href="{{ route('categories.index', ["type" => "home"]) }}"><span class="submenu-dot"></span> {{ __('app.home') }}</a></li>
        </ul>
    </div>
</li>
<li>
    <a href="#testimonials" data-tooltip="{{ __('app.testimonials') }}" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-quote-right"></i>
        <span>{{ __('app.testimonials') }}</span>
        <span class="menu-arrow"><i class="fas fa-chevron-down"></i></span>
    </a>
    <div class="collapse" id="testimonials">
        <ul class="nav-second-level">
            <li><a href="{{ route('testimonials.index', ["type" => "projects"]) }}"><span class="submenu-dot"></span> {{ __('app.projects') }}</a></li>
            <li><a href="{{ route('testimonials.index', ["type" => "organization"]) }}"><span class="submenu-dot"></span> {{ __('app.organization') }}</a></li>
            <li><a href="{{ route('testimonials.index', ["type" => "crowdfunding"]) }}"><span class="submenu-dot"></span> {{ __('app.crowdfunding') }}</a></li>
        </ul>
    </div>
</li>
<li>
    <a href="#stories" data-tooltip="{{ __('app.stories') }}" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-book-open"></i>
        <span>{{ __('app.stories') }}</span>
        <span class="menu-arrow"><i class="fas fa-chevron-down"></i></span>
    </a>
    <div class="collapse" id="stories">
        <ul class="nav-second-level">
            <li><a href="{{ route('stories.index', ["type" => "projects"]) }}"><span class="submenu-dot"></span> {{ __('app.projects') }}</a></li>
            <li><a href="{{ route('stories.index', ["type" => "organization"]) }}"><span class="submenu-dot"></span> {{ __('app.organization') }}</a></li>
            <li><a href="{{ route('stories.index', ["type" => "crowdfunding"]) }}"><span class="submenu-dot"></span> {{ __('app.crowdfunding') }}</a></li>
        </ul>
    </div>
</li>
<li>
    <a href="#partners" data-tooltip="{{ __('app.partners') }}" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-handshake"></i>
        <span>{{ __('app.partners') }}</span>
        <span class="menu-arrow"><i class="fas fa-chevron-down"></i></span>
    </a>
    <div class="collapse" id="partners">
        <ul class="nav-second-level">
            <li><a href="{{ route('partners.index', ["type" => "projects"]) }}"><span class="submenu-dot"></span> {{ __('app.projects') }}</a></li>
            <li><a href="{{ route('partners.index', ["type" => "organization"]) }}"><span class="submenu-dot"></span> {{ __('app.organization') }}</a></li>
            <li><a href="{{ route('partners.index', ["type" => "crowdfunding"]) }}"><span class="submenu-dot"></span> {{ __('app.crowdfunding') }}</a></li>
        </ul>
    </div>
</li>
<li>
    <a href="#facts" data-tooltip="{{ __('app.facts') }}" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-chart-pie"></i>
        <span>{{ __('app.facts') }}</span>
        <span class="menu-arrow"><i class="fas fa-chevron-down"></i></span>
    </a>
    <div class="collapse" id="facts">
        <ul class="nav-second-level">
            <li><a href="{{ route('facts.index', ["type" => "projects"]) }}"><span class="submenu-dot"></span> {{ __('app.projects') }}</a></li>
            <li><a href="{{ route('facts.index', ["type" => "organization"]) }}"><span class="submenu-dot"></span> {{ __('app.organization') }}</a></li>
            <li><a href="{{ route('facts.index', ["type" => "crowdfunding"]) }}"><span class="submenu-dot"></span> {{ __('app.crowdfunding') }}</a></li>
        </ul>
    </div>
</li>
<li>
    <a href="#items" data-tooltip="{{ __('app.items') }}" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-box"></i>
        <span>{{ __('app.items') }}</span>
        <span class="menu-arrow"><i class="fas fa-chevron-down"></i></span>
    </a>
    <div class="collapse" id="items">
        <ul class="nav-second-level">
            <li><a href="{{ route('items.index', ["type" => "home"]) }}"><span class="submenu-dot"></span> {{ __('app.home') }}</a></li>
            <li><a href="{{ route('items.index', ["type" => "projects"]) }}"><span class="submenu-dot"></span> {{ __('app.projects') }}</a></li>
            <li><a href="{{ route('items.index', ["type" => "organization"]) }}"><span class="submenu-dot"></span> {{ __('app.organization') }}</a></li>
            <li><a href="{{ route('items.index', ["type" => "crowdfunding"]) }}"><span class="submenu-dot"></span> {{ __('app.crowdfunding') }}</a></li>
        </ul>
    </div>
</li>
<li>
    <a href="{{ route('announcements.index') }}" data-tooltip="{{ __('app.announcements') }}" class="{{ request()->routeIs('announcements.*') ? 'active-menu' : '' }}">
        <i class="fas fa-bullhorn"></i>
        <span>{{ __('app.announcements') }}</span>
    </a>
</li>
<li>
    <a href="{{ route('about-admin.index') }}" data-tooltip="{{ __('app.about_us') }}" class="{{ request()->routeIs('about-admin.*') ? 'active-menu' : '' }}">
        <i class="fas fa-info-circle"></i>
        <span>{{ __('app.about_us') }}</span>
    </a>
</li>
<li>
    <a href="{{ route('contact-info-admin.edit') }}" data-tooltip="{{ __('app.contact_info') }}" class="{{ request()->routeIs('contact-info-admin.*') ? 'active-menu' : '' }}">
        <i class="fas fa-address-card"></i>
        <span>{{ __('app.contact_info') }}</span>
    </a>
</li>
<li>
    <a href="{{ route('nav-settings.index') }}" data-tooltip="{{ __('app.navigation_visibility') }}" class="{{ request()->routeIs('nav-settings.*') ? 'active-menu' : '' }}">
        <i class="fas fa-bars"></i>
        <span>{{ __('app.navigation_visibility') }}</span>
    </a>
</li>
<li>
    <a href="{{ route('page-maintenance.index') }}" data-tooltip="{{ __('app.page_maintenance') }}" class="{{ request()->routeIs('page-maintenance.*') ? 'active-menu' : '' }}">
        <i class="fas fa-tools"></i>
        <span>{{ __('app.page_maintenance') }}</span>
    </a>
</li>
<li>
    <a href="#newsGallery" data-tooltip="{{ __('app.news_media') }}" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-newspaper"></i>
        <span>{{ __('app.news_media') }}</span>
        <span class="menu-arrow"><i class="fas fa-chevron-down"></i></span>
    </a>
    <div class="collapse" id="newsGallery">
        <ul class="nav-second-level">
            <li>
                <a href="{{ route('news-admin.index') }}">
                    <i class="fas fa-file-alt"></i>
                    <span>{{ __('app.news') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('news-categories-admin.index') }}">
                    <i class="fas fa-tags"></i>
                    <span>{{ __('app.news categories') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('gallery-admin.photos.index') }}">
                    <i class="fas fa-camera"></i>
                    <span>{{ __('app.photos') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('gallery-admin.videos.index') }}">
                    <i class="fas fa-play-circle"></i>
                    <span>{{ __('app.videos') }}</span>
                </a>
            </li>
        </ul>
    </div>
</li>
<li>
    <a href="{{ route('seo.index') }}" data-tooltip="{{ __('app.seo') }}" class="{{ request()->routeIs('seo.*') ? 'active-menu' : '' }}">
        <i class="fas fa-search"></i>
        <span>{{ __('app.seo') }}</span>
    </a>
</li>
<li>
    <a href="{{ route('shortlinks.index') }}" data-tooltip="{{ __('app.short links') }}" class="{{ request()->routeIs('shortlinks.*') ? 'active-menu' : '' }}">
        <i class="fas fa-link"></i>
        <span>{{ __('app.short links') }}</span>
    </a>
</li>

<li class="sidebar-section-title">{{ __('app.business') }}</li>

<li>
    <a href="{{ route('payments.index') }}" data-tooltip="{{ __('app.all_payments') }}" class="{{ request()->routeIs('payments.*') ? 'active-menu' : '' }}">
        <i class="fas fa-credit-card"></i>
        <span>{{ __('app.all_payments') }}</span>
    </a>
</li>

<li>
    <a href="#subscriptionsMenu" data-tooltip="{{ __('app.subscriptions') }}" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-sync-alt"></i>
        <span>{{ __('app.subscriptions') }}</span>
        <span class="menu-arrow"><i class="fas fa-chevron-down"></i></span>
    </a>
    <div class="collapse" id="subscriptionsMenu">
        <ul class="nav-second-level">
            <li><a href="/subscriptions/active"><span class="submenu-dot"></span> {{ __('app.active') }}</a></li>
            <li><a href="/subscriptions/inactive"><span class="submenu-dot"></span> {{ __('app.in active') }}</a></li>
        </ul>
    </div>
</li>

<li>
    <a href="{{ route('collection_team.index') }}" data-tooltip="{{ __('app.collection_team') }}" class="{{ request()->routeIs('collection_team.*') ? 'active-menu' : '' }}">
        <i class="fas fa-user-friends"></i>
        <span>{{ __('app.collection_team') }}</span>
    </a>
</li>

<li>
    <a href="{{ route('excel.index') }}" data-tooltip="{{ __('app.upload sheets') }}" class="{{ request()->routeIs('excel.*') ? 'active-menu' : '' }}">
        <i class="fas fa-file-excel"></i>
        <span>{{ __('app.upload sheets') }}</span>
    </a>
</li>

@if(Auth::user()->id == 6128 || Auth::user()->id == 20)
<li>
    <a href="{{ route('excel.map') }}" data-tooltip="{{ __('app.map items') }}">
        <i class="fas fa-layer-group"></i>
        <span>{{ __('app.map items') }}</span>
    </a>
</li>
@endif

<li class="sidebar-section-title">{{ __('app.customers') }}</li>

<li>
    <a href="{{ route('users.index') }}" data-tooltip="{{ __('app.all_customers') }}" class="{{ request()->routeIs('users.*') ? 'active-menu' : '' }}">
        <i class="fas fa-users"></i>
        <span>{{ __('app.all_customers') }}</span>
    </a>
</li>

<li>
    <a href="{{ route('influencers.index') }}" data-tooltip="{{ __('app.influencers') }}" class="{{ request()->routeIs('influencers.*') ? 'active-menu' : '' }}">
        <i class="fas fa-star"></i>
        <span>{{ __('app.influencers') }}</span>
    </a>
</li>

<li>
    <a href="#admins" data-tooltip="{{ __('app.admin_team') }}" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-user-shield"></i>
        <span>{{ __('app.admin_team') }}</span>
        <span class="menu-arrow"><i class="fas fa-chevron-down"></i></span>
    </a>
    <div class="collapse" id="admins">
        <ul class="nav-second-level">
            <li><a href="/admins"><span class="submenu-dot"></span> {{ __('app.show all') }}</a></li>
            <li><a href="/admins/create"><span class="submenu-dot"></span> {{ __('app.add new') }}</a></li>
        </ul>
    </div>
</li>

<li class="sidebar-section-title">{{ __('app.communications') }}</li>

<li>
    <a href="{{ route('contact_us.index', ["type" => "projects"]) }}" data-tooltip="{{ __('app.user requests') }}" class="{{ request()->is('*/contact_us*') && request()->type == 'projects' ? 'active-menu' : '' }}">
        <i class="fas fa-inbox"></i>
        <span>{{ __('app.user requests') }}</span>
    </a>
</li>
<li>
    <a href="{{ route('contact_us.index', ["type" => "organization"]) }}" data-tooltip="{{ __('app.organization requests') }}" class="{{ request()->is('*/contact_us*') && request()->type == 'organization' ? 'active-menu' : '' }}">
        <i class="fas fa-building"></i>
        <span>{{ __('app.organization requests') }}</span>
    </a>
</li>
<li>
    <a href="/newsletter" data-tooltip="{{ __('app.newsletter') }}" class="{{ request()->is('*/newsletter*') ? 'active-menu' : '' }}">
        <i class="fas fa-paper-plane"></i>
        <span>{{ __('app.newsletter') }}</span>
    </a>
</li>
<li>
    <a href="{{ route('system.notifications') }}" data-tooltip="{{ __('app.notifications') }}" class="{{ request()->routeIs('system.notifications') ? 'active-menu' : '' }}">
        <i class="fas fa-bell"></i>
        <span>{{ __('app.notifications') }}</span>
    </a>
</li>

<li class="sidebar-section-title">{{ __('app.system') }}</li>

<li>
    <a href="{{ route('system.settings') }}" data-tooltip="{{ __('app.general_settings') }}" class="{{ request()->routeIs('system.settings') ? 'active-menu' : '' }}">
        <i class="fas fa-cog"></i>
        <span>{{ __('app.general_settings') }}</span>
    </a>
</li>
<li>
    <a href="{{ route('system.activity-logs') }}" data-tooltip="{{ __('app.activity_logs') }}" class="{{ request()->routeIs('system.activity-logs') ? 'active-menu' : '' }}">
        <i class="fas fa-history"></i>
        <span>{{ __('app.activity_logs') }}</span>
    </a>
</li>
<li>
    <a href="{{ route('system.health') }}" data-tooltip="{{ __('app.system_health') }}" class="{{ request()->routeIs('system.health') ? 'active-menu' : '' }}">
        <i class="fas fa-heartbeat"></i>
        <span>{{ __('app.system_health') }}</span>
    </a>
</li>

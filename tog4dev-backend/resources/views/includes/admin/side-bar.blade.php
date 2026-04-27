<li class="sidebar-section-title">{{ __('app.main') }}</li>

<li>
    <a href="{{ route('dashboard') }}">
        <i class="mdi mdi-view-dashboard-outline"></i>
        <span>{{ __('app.dashboard') }}</span>
    </a>
</li>
<li>
    <a href="{{ route('system.notifications') }}">
        <i class="mdi mdi-bell-outline"></i>
        <span>{{ __('app.notifications') }}</span>
    </a>
</li>
<li>
    <a href="{{ route('system.reports') }}">
        <i class="mdi mdi-chart-arc"></i>
        <span>{{ __('app.reports_center') }}</span>
    </a>
</li>

<li class="sidebar-section-title">{{ __('app.content') }}</li>

<li>
    <a href="#website" data-toggle="collapse">
        <i class="mdi mdi-web"></i>
        <span>{{ __('app.content') }}</span>
        <span class="menu-arrow"></span>
    </a>
    <div class="collapse" id="website">
        <ul class="nav-second-level">
            <li>
                <a href="{{ route('sliders.index') }}">
                    <i class="fas fa-sliders-h"></i>
                    <span>{{ __('app.sliders') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('quick-contributions.index') }}">
                    <i class="fas fa-hand-holding-heart"></i>
                    <span>{{ __('app.contributions') }}</span>
                </a>
            </li>
            <li>
                <a href="#categories" data-toggle="collapse">
                    <i class="fas fa-folder-open"></i>
                    <span>{{ __('app.categories') }}</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="categories">
                    <ul class="nav-second-level">
                        <li>
                            <a href="{{ route('categories.index', ["type" => "projects"]) }}"> <i class="mdi mdi-account-multiple-outline"></i> &nbsp; {{ __('app.projects') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('categories.index', ["type" => "organization"]) }}"> <i class="mdi mdi-domain"></i> &nbsp; {{ __('app.organization') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('categories.index', ["type" => "crowdfunding"]) }}"> <i class="fas fa-users"></i> &nbsp; {{ __('app.crowdfunding') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('categories.index', ["type" => "home"]) }}"> <i class="mdi mdi-account-multiple-outline"></i> &nbsp; {{ __('app.home') }}</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li>
                <a href="#testimonials" data-toggle="collapse">
                    <i class="fas fa-comment"></i>
                    <span>{{ __('app.testimonials') }}</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="testimonials">
                    <ul class="nav-second-level">
                        <li>
                            <a href="{{ route('testimonials.index', ["type" => "projects"]) }}"> <i class="mdi mdi-account-multiple-outline"></i> &nbsp; {{ __('app.projects') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('testimonials.index', ["type" => "organization"]) }}"> <i class="mdi mdi-domain"></i> &nbsp; {{ __('app.organization') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('testimonials.index', ["type" => "crowdfunding"]) }}"> <i class="fas fa-users"></i> &nbsp; {{ __('app.crowdfunding') }}</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li>
                <a href="#stories" data-toggle="collapse">
                    <i class="fas fa-book-open"></i>
                    <span>{{ __('app.stories') }}</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="stories">
                    <ul class="nav-second-level">
                        <li>
                            <a href="{{ route('stories.index', ["type" => "projects"]) }}"> <i class="mdi mdi-account-multiple-outline"></i> &nbsp; {{ __('app.projects') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('stories.index', ["type" => "organization"]) }}"> <i class="mdi mdi-domain"></i> &nbsp; {{ __('app.organization') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('stories.index', ["type" => "crowdfunding"]) }}"> <i class="fas fa-users"></i> &nbsp; {{ __('app.crowdfunding') }}</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li>
                <a href="#partners" data-toggle="collapse">
                    <i class="fas fa-handshake"></i>
                    <span>{{ __('app.partners') }}</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="partners">
                    <ul class="nav-second-level">
                        <li>
                            <a href="{{ route('partners.index', ["type" => "projects"]) }}"> <i class="mdi mdi-account-multiple-outline"></i> &nbsp; {{ __('app.projects') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('partners.index', ["type" => "organization"]) }}"> <i class="mdi mdi-domain"></i> &nbsp; {{ __('app.organization') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('partners.index', ["type" => "crowdfunding"]) }}"> <i class="fas fa-users"></i> &nbsp; {{ __('app.crowdfunding') }}</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li>
                <a href="#facts" data-toggle="collapse">
                    <i class="fas fa-chart-bar"></i>
                    <span>{{ __('app.facts') }}</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="facts">
                    <ul class="nav-second-level">
                        <li>
                            <a href="{{ route('facts.index', ["type" => "projects"]) }}"> <i class="mdi mdi-account-multiple-outline"></i> &nbsp; {{ __('app.projects') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('facts.index', ["type" => "organization"]) }}"> <i class="mdi mdi-domain"></i> &nbsp; {{ __('app.organization') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('facts.index', ["type" => "crowdfunding"]) }}"> <i class="fas fa-users"></i> &nbsp; {{ __('app.crowdfunding') }}</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li>
                <a href="#items" data-toggle="collapse">
                    <i class="fas fa-box"></i>
                    <span>{{ __('app.items') }}</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="items">
                    <ul class="nav-second-level">
                        <li>
                            <a href="{{ route('items.index', ["type" => "home"]) }}"> <i class="mdi mdi-view-dashboard-outline"></i> &nbsp; {{ __('app.home') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('items.index', ["type" => "projects"]) }}"> <i class="mdi mdi-account-multiple-outline"></i> &nbsp; {{ __('app.projects') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('items.index', ["type" => "organization"]) }}"> <i class="mdi mdi-domain"></i> &nbsp; {{ __('app.organization') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('items.index', ["type" => "crowdfunding"]) }}"> <i class="fas fa-users"></i> &nbsp; {{ __('app.crowdfunding') }}</a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</li>

<li>
    <a href="#newsGallery" data-toggle="collapse">
        <i class="mdi mdi-newspaper"></i>
        <span>{{ __('app.news & gallery') }}</span>
        <span class="menu-arrow"></span>
    </a>
    <div class="collapse" id="newsGallery">
        <ul class="nav-second-level">
            <li>
                <a href="{{ route('news-admin.index') }}">
                    <i class="fas fa-newspaper"></i>
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
                    <i class="fas fa-images"></i>
                    <span>{{ __('app.photos') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('gallery-admin.videos.index') }}">
                    <i class="fas fa-video"></i>
                    <span>{{ __('app.videos') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('system.media-library') }}">
                    <i class="fas fa-photo-video"></i>
                    <span>{{ __('app.media_library') }}</span>
                </a>
            </li>
        </ul>
    </div>
</li>

<li class="sidebar-section-title">{{ __('app.business') }}</li>

<li>
    <a href="#paymentsTab" data-toggle="collapse">
        <i class="fas fa-credit-card"></i>
        <span>{{ __('app.payments') }}</span>
        <span class="menu-arrow"></span>
    </a>
    <div class="collapse" id="paymentsTab">
        <ul class="nav-second-level">
            <li>
                <a href="{{ route('payments.index') }}">
                    <i class="mdi mdi-credit-card-multiple"></i>
                    <span>{{ __('app.payments') }}</span>
                </a>
            </li>
            <li>
                <a href="#subscriptions" data-toggle="collapse">
                    <i class="fas fa-sync-alt"></i>
                    <span>{{ __('app.subscriptions') }}</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="subscriptions">
                    <ul class="nav-second-level">
                        <li>
                            <a href="/subscriptions/active"> <i class="fe-eye"></i> &nbsp; {{ __('app.active') }}</a>
                        </li>
                        <li>
                            <a href="/subscriptions/inactive"> <i class="fe-eye"></i> &nbsp; {{ __('app.in active') }}</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li>
                <a href="{{ route('collection_team.index') }}">
                    <i class="fas fa-user-friends"></i>
                    <span>{{ __('app.collection_team') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('excel.index') }}">
                    <i class="fas fa-file-excel"></i>
                    <span>{{ __('app.upload sheets') }}</span>
                </a>
            </li>
            @if(Auth::user()->id == 6128 || Auth::user()->id == 20)
            <li>
                <a href="{{ route('excel.map') }}">
                    <i class="fas fa-layer-group"></i>
                    <span>{{ __('app.map items') }}</span>
                </a>
            </li>
            @endif
        </ul>
    </div>
</li>

<li class="sidebar-section-title">{{ __('app.users list') }}</li>

<li>
    <a href="#users" data-toggle="collapse">
        <i class="mdi mdi-account-group"></i>
        <span>{{ __('app.users') }}</span>
        <span class="menu-arrow"></span>
    </a>
    <div class="collapse" id="users">
        <ul class="nav-second-level">
            <li>
                <a href="{{ route('users.index') }}">
                    <i class="mdi mdi-account-group"></i>
                    <span>{{ __('app.users') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('influencers.index') }}">
                    <i class="fas fa-hand-holding-heart"></i>
                    <span>{{ __('app.influencers') }}</span>
                </a>
            </li>
            <li>
                <a href="#admins" data-toggle="collapse">
                    <i class="fas fa-user-shield"></i>
                    <span>{{ __('app.admins') }}</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="admins">
                    <ul class="nav-second-level">
                        <li>
                            <a href="/admins"> <i class="fe-eye"></i> {{ __('app.show all') }}</a>
                        </li>
                        <li>
                            <a href="/admins/create"> <i class="fe-plus"></i> {{ __('app.add new') }}</a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</li>

<li class="sidebar-section-title">{{ __('app.communications') }}</li>

<li>
    <a href="#communications" data-toggle="collapse">
        <i class="mdi mdi-message-text-outline"></i>
        <span>{{ __('app.communications') }}</span>
        <span class="menu-arrow"></span>
    </a>
    <div class="collapse" id="communications">
        <ul class="nav-second-level">
            <li>
                <a href="#contact_us" data-toggle="collapse">
                    <i class="mdi mdi-account-box-outline"></i>
                    <span>{{ __('app.contact_us') }}</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="contact_us">
                    <ul class="nav-second-level">
                        <li>
                            <a href="{{ route('contact_us.index', ["type" => "projects"]) }}"> <i class="mdi mdi-account-multiple-outline"></i> &nbsp; {{ __('app.user requests') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('contact_us.index', ["type" => "organization"]) }}"> <i class="mdi mdi-domain"></i> &nbsp; {{ __('app.organization requests') }}</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li>
                <a href="/newsletter">
                    <i class="mdi mdi-email-outline"></i>
                    <span>{{ __('app.newsletter') }}</span>
                </a>
            </li>
        </ul>
    </div>
</li>

<li class="sidebar-section-title">{{ __('app.system') }}</li>

<li>
    <a href="#systemMenu" data-toggle="collapse">
        <i class="mdi mdi-cog-outline"></i>
        <span>{{ __('app.system') }}</span>
        <span class="menu-arrow"></span>
    </a>
    <div class="collapse" id="systemMenu">
        <ul class="nav-second-level">
            <li>
                <a href="{{ route('system.activity-logs') }}">
                    <i class="fas fa-history"></i>
                    <span>{{ __('app.activity_logs') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('system.health') }}">
                    <i class="fas fa-heartbeat"></i>
                    <span>{{ __('app.system_health') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('system.settings') }}">
                    <i class="fas fa-cog"></i>
                    <span>{{ __('app.settings') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('seo.index') }}">
                    <i class="fas fa-search"></i>
                    <span>{{ __('app.seo') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('shortlinks.index') }}">
                    <i class="fas fa-link"></i>
                    <span>{{ __('app.short links') }}</span>
                </a>
            </li>
        </ul>
    </div>
</li>

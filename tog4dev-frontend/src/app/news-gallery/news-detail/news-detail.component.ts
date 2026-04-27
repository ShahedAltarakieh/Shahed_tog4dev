import { Component, OnInit, OnDestroy, PLATFORM_ID, Inject } from '@angular/core';
import { isPlatformBrowser } from '@angular/common';
import { ActivatedRoute, RouterLink } from '@angular/router';
import { TranslatePipe } from '@ngx-translate/core';
import { Subject, takeUntil, skip } from 'rxjs';

import { StorageService } from 'app/core/storage/storage.service';
import { NewsService } from '../services/news.service';
import { NewsItem } from '../types/news-gallery.types';

@Component({
    selector: 'app-news-detail',
    standalone: true,
    imports: [TranslatePipe, RouterLink],
    templateUrl: './news-detail.component.html',
    styleUrl: './news-detail.component.scss'
})
export class NewsDetailComponent implements OnInit, OnDestroy {
    article: NewsItem | null = null;
    relatedNews: NewsItem[] = [];
    loading: boolean = true;
    hasError: boolean = false;
    linkCopied: boolean = false;
    isBrowser: boolean;
    destroy$ = new Subject<void>();

    constructor(
        public storageService: StorageService,
        private newsService: NewsService,
        private route: ActivatedRoute,
        @Inject(PLATFORM_ID) platformId: Object
    ) {
        this.isBrowser = isPlatformBrowser(platformId);
    }

    ngOnInit(): void {
        this.route.params.pipe(takeUntil(this.destroy$)).subscribe(params => {
            const slug = params['slug'];
            if (slug) {
                this.fetchArticle(slug);
            }
        });

        this.storageService.siteLanguage$.pipe(
            skip(1),
            takeUntil(this.destroy$)
        ).subscribe(() => {
            const slug = this.route.snapshot.params['slug'];
            if (slug) {
                this.fetchArticle(slug);
            }
        });
    }

    ngOnDestroy(): void {
        this.destroy$.next();
        this.destroy$.complete();
    }

    fetchArticle(slug: string): void {
        this.loading = true;
        this.article = null;
        this.relatedNews = [];
        this.hasError = false;
        const lang = this.storageService.siteLanguage$.value as 'ar' | 'en';
        this.newsService.getNewsArticle(lang, slug).subscribe({
            next: (res: any) => {
                if (res && res.data) {
                    this.article = res.data;
                } else if (res) {
                    this.article = res;
                }
                this.loading = false;
                if (this.article) {
                    this.fetchRelated(slug);
                }
            },
            error: () => {
                this.article = null;
                this.hasError = true;
                this.loading = false;
            }
        });
    }

    fetchRelated(slug: string): void {
        const lang = this.storageService.siteLanguage$.value as 'ar' | 'en';
        this.newsService.getRelatedNews(lang, slug).subscribe({
            next: (res) => {
                if (res && res.data) {
                    this.relatedNews = res.data;
                } else if (res && Array.isArray(res)) {
                    this.relatedNews = res;
                }
            },
            error: () => {}
        });
    }

    getNewsListRoute(): string {
        const lang = this.storageService.siteLanguage$.value;
        return lang === 'ar' ? '/ar/الأخبار' : '/en/news';
    }

    getNewsDetailRoute(slug: string): string {
        const lang = this.storageService.siteLanguage$.value;
        return lang === 'ar' ? '/ar/الأخبار/' + slug : '/en/news/' + slug;
    }

    formatDate(dateStr: string): string {
        if (!dateStr) return '';
        const date = new Date(dateStr);
        const lang = this.storageService.siteLanguage$.value;
        return date.toLocaleDateString(lang === 'ar' ? 'ar-JO' : 'en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }

    getCurrentUrl(): string {
        if (!this.isBrowser) return '';
        return window.location.href;
    }

    shareOnFacebook(): void {
        if (!this.isBrowser) return;
        const url = encodeURIComponent(this.getCurrentUrl());
        window.open('https://www.facebook.com/sharer/sharer.php?u=' + url, '_blank', 'width=600,height=400');
    }

    shareOnWhatsApp(): void {
        if (!this.isBrowser) return;
        const url = encodeURIComponent(this.getCurrentUrl());
        const text = encodeURIComponent(this.article?.title || '');
        window.open('https://wa.me/?text=' + text + '%20' + url, '_blank');
    }

    shareOnTwitter(): void {
        if (!this.isBrowser) return;
        const url = encodeURIComponent(this.getCurrentUrl());
        const text = encodeURIComponent(this.article?.title || '');
        window.open('https://twitter.com/intent/tweet?url=' + url + '&text=' + text, '_blank', 'width=600,height=400');
    }

    shareOnTelegram(): void {
        if (!this.isBrowser) return;
        const url = encodeURIComponent(this.getCurrentUrl());
        const text = encodeURIComponent(this.article?.title || '');
        window.open('https://t.me/share/url?url=' + url + '&text=' + text, '_blank', 'width=600,height=400');
    }

    shareOnLinkedIn(): void {
        if (!this.isBrowser) return;
        const url = encodeURIComponent(this.getCurrentUrl());
        window.open('https://www.linkedin.com/sharing/share-offsite/?url=' + url, '_blank', 'width=600,height=400');
    }

    shareOnInstagram(): void {
        if (!this.isBrowser) return;
        this.copyLink();
    }

    shareOnSnapchat(): void {
        if (!this.isBrowser) return;
        const url = encodeURIComponent(this.getCurrentUrl());
        window.open('https://www.snapchat.com/share?link=' + url, '_blank', 'width=600,height=400');
    }

    shareViaEmail(): void {
        if (!this.isBrowser) return;
        const subject = encodeURIComponent(this.article?.title || '');
        const body = encodeURIComponent(this.getCurrentUrl());
        window.open('mailto:?subject=' + subject + '&body=' + body);
    }

    copyLink(): void {
        if (!this.isBrowser) return;
        navigator.clipboard.writeText(this.getCurrentUrl()).then(() => {
            this.linkCopied = true;
            setTimeout(() => { this.linkCopied = false; }, 2000);
        });
    }
}

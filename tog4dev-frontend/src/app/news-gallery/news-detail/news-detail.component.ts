import { Component, OnInit, OnDestroy } from '@angular/core';
import { ActivatedRoute, RouterLink } from '@angular/router';
import { TranslatePipe } from '@ngx-translate/core';
import { NgClass } from '@angular/common';
import { Subject, takeUntil } from 'rxjs';

import { StorageService } from 'app/core/storage/storage.service';
import { NewsService } from '../services/news.service';
import { NewsItem } from '../types/news-gallery.types';

@Component({
    selector: 'app-news-detail',
    standalone: true,
    imports: [TranslatePipe, RouterLink, NgClass],
    templateUrl: './news-detail.component.html',
    styleUrl: './news-detail.component.scss'
})
export class NewsDetailComponent implements OnInit, OnDestroy {
    article: NewsItem | null = null;
    relatedNews: NewsItem[] = [];
    loading: boolean = true;
    destroy$ = new Subject<void>();

    constructor(
        public storageService: StorageService,
        private newsService: NewsService,
        private route: ActivatedRoute
    ) {}

    ngOnInit(): void {
        this.route.params.pipe(takeUntil(this.destroy$)).subscribe(params => {
            const slug = params['slug'];
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
                this.loading = false;
            }
        });
    }

    fetchRelated(slug: string): void {
        const lang = this.storageService.siteLanguage$.value as 'ar' | 'en';
        this.newsService.getRelatedNews(lang, slug).subscribe(res => {
            if (res && res.data) {
                this.relatedNews = res.data;
            } else if (res && Array.isArray(res)) {
                this.relatedNews = res;
            }
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
}

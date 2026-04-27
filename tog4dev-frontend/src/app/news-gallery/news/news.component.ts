import { Component, OnInit, OnDestroy } from '@angular/core';
import { ActivatedRoute, RouterLink } from '@angular/router';
import { TranslatePipe } from '@ngx-translate/core';
import { FormsModule } from '@angular/forms';
import { NgClass } from '@angular/common';
import { Subject, takeUntil, debounceTime, distinctUntilChanged } from 'rxjs';

import { StorageService } from 'app/core/storage/storage.service';
import { NewsService } from '../services/news.service';
import { NewsItem, NewsCategory } from '../types/news-gallery.types';

@Component({
    selector: 'app-news',
    standalone: true,
    imports: [TranslatePipe, RouterLink, FormsModule, NgClass],
    templateUrl: './news.component.html',
    styleUrl: './news.component.scss'
})
export class NewsComponent implements OnInit, OnDestroy {
    newsList: NewsItem[] = [];
    categories: NewsCategory[] = [];
    selectedCategory: string = '';
    searchQuery: string = '';
    currentPage: number = 1;
    totalPages: number = 1;
    totalItems: number = 0;
    loading: boolean = false;
    hasError: boolean = false;
    destroy$ = new Subject<void>();
    searchSubject$ = new Subject<string>();

    constructor(
        public storageService: StorageService,
        private newsService: NewsService,
        private route: ActivatedRoute
    ) {}

    ngOnInit(): void {
        this.searchSubject$.pipe(
            debounceTime(400),
            distinctUntilChanged(),
            takeUntil(this.destroy$)
        ).subscribe(query => {
            this.searchQuery = query;
            this.currentPage = 1;
            this.fetchNews();
        });

        this.storageService.siteLanguage$.pipe(takeUntil(this.destroy$)).subscribe(() => {
            this.fetchCategories();
            this.fetchNews();
        });
    }

    ngOnDestroy(): void {
        this.destroy$.next();
        this.destroy$.complete();
    }

    fetchCategories(): void {
        const lang = this.storageService.siteLanguage$.value as 'ar' | 'en';
        this.newsService.getCategories(lang).subscribe({
            next: (res) => {
                if (res) this.categories = res.data;
            },
            error: () => {}
        });
    }

    fetchNews(): void {
        this.loading = true;
        this.hasError = false;
        const lang = this.storageService.siteLanguage$.value as 'ar' | 'en';
        this.newsService.getNews(lang, {
            category: this.selectedCategory || undefined,
            search: this.searchQuery || undefined,
            page: this.currentPage,
            perPage: 12,
        }).subscribe({
            next: (res) => {
                if (res) {
                    this.newsList = res.data;
                    this.totalPages = res.meta.last_page;
                    this.totalItems = res.meta.total;
                }
                this.loading = false;
            },
            error: () => {
                this.hasError = true;
                this.loading = false;
            }
        });
    }

    onCategoryChange(slug: string): void {
        this.selectedCategory = slug;
        this.currentPage = 1;
        this.fetchNews();
    }

    onSearchInput(value: string): void {
        this.searchSubject$.next(value);
    }

    goToPage(page: number): void {
        if (page >= 1 && page <= this.totalPages) {
            this.currentPage = page;
            this.fetchNews();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }

    getNewsDetailRoute(slug: string): string {
        const lang = this.storageService.siteLanguage$.value;
        if (lang === 'ar') {
            return '/' + lang + '/الأخبار/' + slug;
        }
        return '/' + lang + '/news/' + slug;
    }

    getPages(): number[] {
        const pages: number[] = [];
        const start = Math.max(1, this.currentPage - 2);
        const end = Math.min(this.totalPages, this.currentPage + 2);
        for (let i = start; i <= end; i++) {
            pages.push(i);
        }
        return pages;
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

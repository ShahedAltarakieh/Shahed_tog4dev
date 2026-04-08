import { Component, OnInit, OnDestroy } from '@angular/core';
import { RouterLink } from '@angular/router';
import { TranslatePipe } from '@ngx-translate/core';
import { FormsModule } from '@angular/forms';
import { Subject, takeUntil, debounceTime, distinctUntilChanged, catchError, of, forkJoin } from 'rxjs';

import { StorageService } from 'app/core/storage/storage.service';
import { NewsService } from '../services/news.service';
import { GalleryService } from '../services/gallery.service';
import { NewsItem, GalleryPhoto, GalleryVideo } from '../types/news-gallery.types';

@Component({
    selector: 'app-news-gallery-main',
    standalone: true,
    imports: [TranslatePipe, RouterLink, FormsModule],
    templateUrl: './news-gallery-main.component.html',
    styleUrl: './news-gallery-main.component.scss'
})
export class NewsGalleryMainComponent implements OnInit, OnDestroy {
    newsList: NewsItem[] = [];
    photos: GalleryPhoto[] = [];
    videos: GalleryVideo[] = [];
    loading: boolean = true;
    hasError: boolean = false;
    searchQuery: string = '';
    searchSubject$ = new Subject<string>();
    destroy$ = new Subject<void>();

    constructor(
        public storageService: StorageService,
        private newsService: NewsService,
        private galleryService: GalleryService
    ) {}

    ngOnInit(): void {
        this.searchSubject$.pipe(
            debounceTime(400),
            distinctUntilChanged(),
            takeUntil(this.destroy$)
        ).subscribe(query => {
            this.searchQuery = query;
            this.fetchAll();
        });

        this.storageService.siteLanguage$.pipe(takeUntil(this.destroy$)).subscribe(() => {
            this.fetchAll();
        });
    }

    ngOnDestroy(): void {
        this.destroy$.next();
        this.destroy$.complete();
    }

    fetchAll(): void {
        this.loading = true;
        this.hasError = false;
        const lang = this.storageService.siteLanguage$.value as 'ar' | 'en';
        const searchParam = this.searchQuery || undefined;

        forkJoin({
            news: this.newsService.getNews(lang, { perPage: 4, search: searchParam }).pipe(
                catchError(() => of({ data: [], meta: { current_page: 1, from: null, last_page: 1, path: '', per_page: 4, to: null, total: 0 }, links: { first: '', last: '', prev: null, next: null } }))
            ),
            photos: this.galleryService.getPhotos(lang, { perPage: 4, search: searchParam }).pipe(
                catchError(() => of({ data: [], meta: { current_page: 1, from: null, last_page: 1, path: '', per_page: 4, to: null, total: 0 }, links: { first: '', last: '', prev: null, next: null } }))
            ),
            videos: this.galleryService.getVideos(lang, { perPage: 4, search: searchParam }).pipe(
                catchError(() => of({ data: [], meta: { current_page: 1, from: null, last_page: 1, path: '', per_page: 4, to: null, total: 0 }, links: { first: '', last: '', prev: null, next: null } }))
            ),
        }).subscribe({
            next: (res) => {
                this.newsList = res.news?.data || [];
                this.photos = res.photos?.data || [];
                this.videos = res.videos?.data || [];
                this.loading = false;
            },
            error: () => {
                this.hasError = true;
                this.loading = false;
            }
        });
    }

    onSearchInput(value: string): void {
        this.searchSubject$.next(value);
    }

    getNewsRoute(): string {
        const lang = this.storageService.siteLanguage$.value;
        return lang === 'ar' ? '/ar/الأخبار' : '/en/news';
    }

    getNewsDetailRoute(slug: string): string {
        const lang = this.storageService.siteLanguage$.value;
        return lang === 'ar' ? '/ar/الأخبار/' + slug : '/en/news/' + slug;
    }

    getPhotosRoute(): string {
        const lang = this.storageService.siteLanguage$.value;
        return lang === 'ar' ? '/ar/الصور' : '/en/photos';
    }

    getVideosRoute(): string {
        const lang = this.storageService.siteLanguage$.value;
        return lang === 'ar' ? '/ar/الفيديو' : '/en/videos';
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

    getThumbnailUrl(video: GalleryVideo): string | null {
        if (video.thumbnail) return video.thumbnail;
        const ytMatch = video.video_url?.match(/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/);
        if (ytMatch) {
            return 'https://img.youtube.com/vi/' + ytMatch[1] + '/hqdefault.jpg';
        }
        return null;
    }

    get hasNoResults(): boolean {
        return !this.loading && !this.hasError &&
            this.newsList.length === 0 && this.photos.length === 0 && this.videos.length === 0;
    }
}

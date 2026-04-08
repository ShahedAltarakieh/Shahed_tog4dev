import { Component, OnInit, OnDestroy } from '@angular/core';
import { TranslatePipe } from '@ngx-translate/core';
import { FormsModule } from '@angular/forms';
import { NgClass } from '@angular/common';
import { DomSanitizer, SafeResourceUrl } from '@angular/platform-browser';
import { Subject, takeUntil, debounceTime, distinctUntilChanged } from 'rxjs';

import { StorageService } from 'app/core/storage/storage.service';
import { GalleryService } from '../services/gallery.service';
import { NewsService } from '../services/news.service';
import { GalleryVideo, NewsCategory } from '../types/news-gallery.types';

@Component({
    selector: 'app-videos',
    standalone: true,
    imports: [TranslatePipe, FormsModule, NgClass],
    templateUrl: './videos.component.html',
    styleUrl: './videos.component.scss'
})
export class VideosComponent implements OnInit, OnDestroy {
    videos: GalleryVideo[] = [];
    categories: NewsCategory[] = [];
    selectedCategory: string = '';
    searchQuery: string = '';
    currentPage: number = 1;
    totalPages: number = 1;
    loading: boolean = false;
    hasError: boolean = false;
    activeVideo: GalleryVideo | null = null;
    activeVideoUrl: SafeResourceUrl | null = null;
    destroy$ = new Subject<void>();
    searchSubject$ = new Subject<string>();

    constructor(
        public storageService: StorageService,
        private galleryService: GalleryService,
        private newsService: NewsService,
        private sanitizer: DomSanitizer
    ) {}

    ngOnInit(): void {
        this.searchSubject$.pipe(
            debounceTime(400),
            distinctUntilChanged(),
            takeUntil(this.destroy$)
        ).subscribe(query => {
            this.searchQuery = query;
            this.currentPage = 1;
            this.fetchVideos();
        });

        this.storageService.siteLanguage$.pipe(takeUntil(this.destroy$)).subscribe(() => {
            this.newsService.getCategories(this.storageService.siteLanguage$.value as 'ar' | 'en').subscribe({
                next: (res) => {
                    if (res) this.categories = res.data;
                },
                error: () => {}
            });
            this.fetchVideos();
        });
    }

    ngOnDestroy(): void {
        this.destroy$.next();
        this.destroy$.complete();
    }

    fetchVideos(): void {
        this.loading = true;
        this.hasError = false;
        const lang = this.storageService.siteLanguage$.value as 'ar' | 'en';
        this.galleryService.getVideos(lang, {
            category: this.selectedCategory || undefined,
            search: this.searchQuery || undefined,
            page: this.currentPage,
            perPage: 12,
        }).subscribe({
            next: (res) => {
                if (res) {
                    this.videos = res.data;
                    this.totalPages = res.meta.last_page;
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
        this.fetchVideos();
    }

    onSearchInput(value: string): void {
        this.searchSubject$.next(value);
    }

    goToPage(page: number): void {
        if (page >= 1 && page <= this.totalPages) {
            this.currentPage = page;
            this.fetchVideos();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }

    playVideo(video: GalleryVideo): void {
        this.activeVideo = video;
        this.activeVideoUrl = this.getEmbedUrl(video.video_url);
    }

    closePlayer(): void {
        this.activeVideo = null;
        this.activeVideoUrl = null;
    }

    getEmbedUrl(url: string): SafeResourceUrl | null {
        if (!url) return null;

        const ytMatch = url.match(/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/);
        if (ytMatch) {
            return this.sanitizer.bypassSecurityTrustResourceUrl(
                'https://www.youtube.com/embed/' + ytMatch[1] + '?autoplay=1'
            );
        }

        const vimeoMatch = url.match(/vimeo\.com\/(\d+)/);
        if (vimeoMatch) {
            return this.sanitizer.bypassSecurityTrustResourceUrl(
                'https://player.vimeo.com/video/' + vimeoMatch[1] + '?autoplay=1'
            );
        }

        return null;
    }

    getThumbnailUrl(video: GalleryVideo): string | null {
        if (video.thumbnail) return video.thumbnail;

        const ytMatch = video.video_url?.match(/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/);
        if (ytMatch) {
            return 'https://img.youtube.com/vi/' + ytMatch[1] + '/hqdefault.jpg';
        }

        return null;
    }

    getPages(): number[] {
        const pages: number[] = [];
        const start = Math.max(1, this.currentPage - 2);
        const end = Math.min(this.totalPages, this.currentPage + 2);
        for (let i = start; i <= end; i++) pages.push(i);
        return pages;
    }
}

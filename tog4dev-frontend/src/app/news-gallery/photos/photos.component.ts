import { Component, OnInit, OnDestroy, Inject, PLATFORM_ID, HostListener } from '@angular/core';
import { isPlatformBrowser } from '@angular/common';
import { TranslatePipe } from '@ngx-translate/core';
import { FormsModule } from '@angular/forms';
import { NgClass } from '@angular/common';
import { Subject, takeUntil, debounceTime, distinctUntilChanged } from 'rxjs';

import { StorageService } from 'app/core/storage/storage.service';
import { GalleryService } from '../services/gallery.service';
import { NewsService } from '../services/news.service';
import { GalleryPhoto, NewsCategory } from '../types/news-gallery.types';

@Component({
    selector: 'app-photos',
    standalone: true,
    imports: [TranslatePipe, FormsModule, NgClass],
    templateUrl: './photos.component.html',
    styleUrl: './photos.component.scss'
})
export class PhotosComponent implements OnInit, OnDestroy {
    photos: GalleryPhoto[] = [];
    categories: NewsCategory[] = [];
    selectedCategory: string = '';
    searchQuery: string = '';
    currentPage: number = 1;
    totalPages: number = 1;
    loading: boolean = false;
    hasError: boolean = false;
    lightboxIndex: number | null = null;
    get currentLightboxPhoto(): GalleryPhoto | null {
        return this.lightboxIndex !== null ? this.photos[this.lightboxIndex] : null;
    }
    destroy$ = new Subject<void>();
    searchSubject$ = new Subject<string>();
    private isBrowser: boolean;

    constructor(
        public storageService: StorageService,
        private galleryService: GalleryService,
        private newsService: NewsService,
        @Inject(PLATFORM_ID) platformId: Object
    ) {
        this.isBrowser = isPlatformBrowser(platformId);
    }

    ngOnInit(): void {
        this.searchSubject$.pipe(
            debounceTime(400),
            distinctUntilChanged(),
            takeUntil(this.destroy$)
        ).subscribe(query => {
            this.searchQuery = query;
            this.currentPage = 1;
            this.fetchPhotos();
        });

        this.storageService.siteLanguage$.pipe(takeUntil(this.destroy$)).subscribe(() => {
            this.newsService.getCategories(this.storageService.siteLanguage$.value as 'ar' | 'en').subscribe({
                next: (res) => {
                    if (res) this.categories = res.data;
                },
                error: () => {}
            });
            this.fetchPhotos();
        });
    }

    ngOnDestroy(): void {
        this.destroy$.next();
        this.destroy$.complete();
    }

    fetchPhotos(): void {
        this.loading = true;
        this.hasError = false;
        const lang = this.storageService.siteLanguage$.value as 'ar' | 'en';
        this.galleryService.getPhotos(lang, {
            category: this.selectedCategory || undefined,
            search: this.searchQuery || undefined,
            page: this.currentPage,
            perPage: 12,
        }).subscribe({
            next: (res) => {
                if (res) {
                    this.photos = res.data;
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
        this.fetchPhotos();
    }

    onSearchInput(value: string): void {
        this.searchSubject$.next(value);
    }

    goToPage(page: number): void {
        if (page >= 1 && page <= this.totalPages) {
            this.currentPage = page;
            this.fetchPhotos();
            if (this.isBrowser) {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        }
    }

    openLightbox(index: number): void {
        this.lightboxIndex = index;
    }

    closeLightbox(): void {
        this.lightboxIndex = null;
    }

    prevPhoto(event: Event): void {
        event.stopPropagation();
        if (this.lightboxIndex !== null && this.lightboxIndex > 0) {
            this.lightboxIndex--;
        }
    }

    nextPhoto(event: Event): void {
        event.stopPropagation();
        if (this.lightboxIndex !== null && this.lightboxIndex < this.photos.length - 1) {
            this.lightboxIndex++;
        }
    }

    @HostListener('document:keydown', ['$event'])
    handleKeydown(event: KeyboardEvent): void {
        if (this.lightboxIndex === null) return;
        if (event.key === 'ArrowLeft') this.prevPhoto(event);
        else if (event.key === 'ArrowRight') this.nextPhoto(event);
        else if (event.key === 'Escape') this.closeLightbox();
    }

    getPages(): number[] {
        const pages: number[] = [];
        const start = Math.max(1, this.currentPage - 2);
        const end = Math.min(this.totalPages, this.currentPage + 2);
        for (let i = start; i <= end; i++) pages.push(i);
        return pages;
    }
}

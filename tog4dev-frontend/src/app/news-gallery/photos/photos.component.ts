import { Component, OnInit, OnDestroy } from '@angular/core';
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
    lightboxPhoto: GalleryPhoto | null = null;
    destroy$ = new Subject<void>();
    searchSubject$ = new Subject<string>();

    constructor(
        public storageService: StorageService,
        private galleryService: GalleryService,
        private newsService: NewsService
    ) {}

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
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }

    openLightbox(photo: GalleryPhoto): void {
        this.lightboxPhoto = photo;
    }

    closeLightbox(): void {
        this.lightboxPhoto = null;
    }

    getPages(): number[] {
        const pages: number[] = [];
        const start = Math.max(1, this.currentPage - 2);
        const end = Math.min(this.totalPages, this.currentPage + 2);
        for (let i = start; i <= end; i++) pages.push(i);
        return pages;
    }
}

import { Component, OnDestroy, OnInit } from '@angular/core';

import {Subject, Subscription, takeUntil} from 'rxjs';

import { OurStoriesService } from "../shared/components/our-stories/services/our-stories.service";
import { TestimonialsService } from "../shared/components/testimonials/services/testimonials.service";
import { StorageService } from 'app/core/storage/storage.service';

import { HeroSectionComponent } from "./components/hero-section/hero-section.component";
import { OurPartnersComponent } from "../shared/components/our-partners/our-partners.component";
import { OurStoriesComponent } from "../shared/components/our-stories/our-stories.component";
import { TestimonialsComponent } from "../shared/components/testimonials/testimonials.component";

import { Story } from "../shared/components/our-stories/types/our-stories.types";
import { Testimonial } from "../shared/components/testimonials/types/testimonial.types";
import {FactsAndStatisticsComponent} from "../shared/components/facts-and-statistics/facts-and-statistics.component";
import {ProjectListItemComponent} from "../shared/components/project-list-item/project-list-item.component";
import {CategoriesService} from "../shared/services/categories/categories.service";
import {Category} from "../shared/services/types/categories.types";
import {FactAndStatisticsService} from "../shared/components/facts-and-statistics/services/fact-and-statistics.service";
import {Fact} from "../shared/components/facts-and-statistics/types/fact-and-statistics.types";
import {Partner} from "../shared/components/our-partners/types/our-partners.types";
import {OurPartnersService} from "../shared/components/our-partners/services/our-partners.service";
import {ActivatedRoute, Router} from "@angular/router";
import {NgIf} from "@angular/common";
import {TranslatePipe} from "@ngx-translate/core";
import {ProjectsService} from "../shared/services/projects/projects.service";
import {ProjectItem} from "../shared/components/project-list-item/types/project-list-item.types";
import {Meta} from "@angular/platform-browser";

@Component({
    selector: 'app-ramadan',
    imports: [
        OurPartnersComponent,
        OurStoriesComponent,
        TestimonialsComponent,
        HeroSectionComponent,
        FactsAndStatisticsComponent,
        ProjectListItemComponent,
        NgIf,
        TranslatePipe
    ],
    templateUrl: './ramadan.component.html',
    styleUrl: './ramadan.component.scss'
})
export class RamadanComponent implements OnInit, OnDestroy {
  constructor(public storageService: StorageService,
              public ourStoriesService: OurStoriesService,
              public testimonialsService: TestimonialsService,
              public factAndStatisticsService: FactAndStatisticsService,
              public categoriesService: CategoriesService,
              public ourPartnersService: OurPartnersService,
              public projectsService: ProjectsService,
              public metaService: Meta,
              private router: Router,
              private route: ActivatedRoute) {}

  type: string = 'ramadan';
  id: number | null = null;
  slug: string | null = null;
  selected_category!: Category;
  destory$ = new Subject<void>;
  storiesList: Story[] = [];
  testimonialsList: Testimonial[] = [];
  categoriesList: Category[] = [];
  factsList: Fact[] = [];
  projectsList: ProjectItem[] = [];
  partnersList: Partner[] = [];
  lang: string = '';
  private routeSub!: Subscription;

    /**
   * Angular afterViewInit lifecycle method
   */
    ngOnInit(): void {
        this.lang = this.storageService.siteLanguage$.value;
        this.fetchData()
    }

    scrollToFragment(fragment: string): void {
        // Delay to ensure the section is loaded
        setTimeout(() => {
            if (typeof document === 'undefined') return;
            const element = document.getElementById(fragment);
            if (element) {
                const elementPosition = element.getBoundingClientRect().top + (typeof window !== 'undefined' ? window.pageYOffset : 0);
                const offset = 150;
                if (typeof window !== 'undefined') window.scrollTo({
                    top: elementPosition - offset, // Scroll to the element position minus the offset
                    behavior: 'smooth' // Smooth scrolling
                });
            }
        }, 300); // Adjust the delay based on your data fetching time
    }

    fetchData() {
    this.storageService.siteLanguage$.pipe(takeUntil(this.destory$)).subscribe(lang => {
        if(lang != this.lang && this.slug != null){
            this.lang == lang;
            if(lang == "ar"){
                this.router.navigate(["ar/درب-العطاء-لأمي"]);
            } else {
                this.router.navigate(["en/mama-giving-shope"]);
            }
        }
        var id: number | null = null;
        var id_to_empty: number | null = null;
        this.categoriesService.getCategories(lang, this.type).subscribe(value => {
            this.categoriesList = value.data;
            this.selected_category = this.categoriesList[0];
            
            this.updateMetaTags();
            id = null;
            id_to_empty = null;
            this.ourStoriesService.getStories(lang, this.type, id_to_empty).subscribe(value => this.storiesList = value.data);
            this.testimonialsService.getTestimonials(lang, this.type, id_to_empty).subscribe(value => this.testimonialsList = value.data);
            this.factAndStatisticsService.getFactsAndStatistics(lang, 'crowdfunding', 24).subscribe(value => this.factsList = value.data);
            this.ourPartnersService.getPartners(lang, this.type, id_to_empty).subscribe(value => this.partnersList = value.data);
            this.projectsService.getProjects(lang, "ramadan", id_to_empty).subscribe(value => {
                this.projectsList = value?.data ?? [];
            });
        });
    });
  }


  /**
   * Angular onDestory lifecycle method
   */
  ngOnDestroy(): void {
    if (this.routeSub) {
        this.routeSub.unsubscribe();
    }
    this.destory$.next();
    this.destory$.complete();
  }

    updateMetaTags(): void {
        // Update standard meta tags
        this.metaService.updateTag({
            name: 'description',
            content: this.selected_category.description
        });

        // Update Open Graph meta tags
        this.metaService.updateTag({
            property: 'og:title',
            content: this.selected_category.title
        });
        this.metaService.updateTag({
            property: 'og:description',
            content: this.selected_category.description
        });
        this.metaService.updateTag({
            property: 'og:image',
            content: this.selected_category.hero_image
        });
        this.metaService.updateTag({
            property: 'og:url',
            content: typeof window !== 'undefined' ? window.location.href : ''
        });
        this.metaService.updateTag({
            property: 'og:type',
            content: 'website'
        });

        // Update Twitter Card meta tags
        this.metaService.updateTag({
            name: 'twitter:card',
            content: this.selected_category.hero_image
        });
        this.metaService.updateTag({
            name: 'twitter:title',
            content: this.selected_category.title
        });
        this.metaService.updateTag({
            name: 'twitter:description',
            content: this.selected_category.description
        });
        this.metaService.updateTag({
            name: 'twitter:image',
            content: this.selected_category.hero_image
        });
    }

    resetComponentData(): void {
        this.storiesList = [];
        this.testimonialsList = [];
        this.factsList = [];
        this.projectsList = [];
        this.partnersList = [];
    }
      
}

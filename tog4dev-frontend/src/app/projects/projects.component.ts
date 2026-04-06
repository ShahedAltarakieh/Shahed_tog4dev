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
import {CategoriesListComponent} from "../shared/components/categories-list/categories-list.component";
import {ProjectListItemComponent} from "../shared/components/project-list-item/project-list-item.component";
import {CategoriesService} from "../shared/services/categories/categories.service";
import {Category} from "../shared/services/types/categories.types";
import {FactAndStatisticsService} from "../shared/components/facts-and-statistics/services/fact-and-statistics.service";
import {Fact} from "../shared/components/facts-and-statistics/types/fact-and-statistics.types";
import {Partner} from "../shared/components/our-partners/types/our-partners.types";
import {OurPartnersService} from "../shared/components/our-partners/services/our-partners.service";
import {ActivatedRoute, Router} from "@angular/router";
import {NgIf} from "@angular/common";
import {QuickContributionService} from "../shared/services/quick-contribution/quick-contribution.service";
import {TranslatePipe} from "@ngx-translate/core";
import {ProjectsService} from "../shared/services/projects/projects.service";
import {ProjectItem} from "../shared/components/project-list-item/types/project-list-item.types";
import {Meta} from "@angular/platform-browser";

@Component({
    selector: 'app-projects',
    imports: [
        OurPartnersComponent,
        OurStoriesComponent,
        TestimonialsComponent,
        HeroSectionComponent,
        FactsAndStatisticsComponent,
        CategoriesListComponent,
        ProjectListItemComponent,
        NgIf,
        TranslatePipe
    ],
    templateUrl: './projects.component.html',
    styleUrl: './projects.component.scss'
})
export class ProjectsComponent implements OnInit, OnDestroy {
  constructor(public storageService: StorageService,
              public ourStoriesService: OurStoriesService,
              public testimonialsService: TestimonialsService,
              public factAndStatisticsService: FactAndStatisticsService,
              public categoriesService: CategoriesService,
              public ourPartnersService: OurPartnersService,
              public projectsService: ProjectsService,
              public metaService: Meta,
              private router: Router,
              public quickContributionService: QuickContributionService,
              private route: ActivatedRoute) {}

  type: string = 'projects';
  id: number | null = null;
  slug: string | null = null;
  slug_en: string | null = null;
  selected_category!: Category;
  destory$ = new Subject<void>;
  storiesList: Story[] = [];
  testimonialsList: Testimonial[] = [];
  categoriesList: Category[] = [];
  factsList: Fact[] = [];
  projectsList: ProjectItem[] = [];
  partnersList: Partner[] = [];
  quickContribution: any;
  lang: string = '';
  private routeSub!: Subscription;

    /**
   * Angular afterViewInit lifecycle method
   */
    ngOnInit(): void {
        this.lang = this.storageService.siteLanguage$.value;
        this.route.paramMap.subscribe(params => {
            const newSlug = params.get('category_slug');

            if (this.slug !== newSlug) {
              this.destory$.next();
              this.resetComponentData();
            }
          
            this.slug = newSlug;
            this.fetchData();          
        });
        this.route.fragment.subscribe((fragment) => {
            if (fragment) {
                this.scrollToFragment(fragment);
            }
        });
    }

    scrollToFragment(fragment: string): void {
        // Delay to ensure the section is loaded
        setTimeout(() => {
            const element = document.getElementById(fragment);
            if (element) {
                const elementPosition = element.getBoundingClientRect().top + window.pageYOffset;
                const offset = 150; // Adjust the offset value (e.g., 20px)
                window.scrollTo({
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
                this.router.navigate(["ar/المشاريع-الفردية", this.selected_category.slug_ar]);
            } else {
                this.router.navigate(["en/individual-projects", this.selected_category.slug_en]);
            }
        }
        var id: number | null = null;
        var id_to_empty: number | null = null;
        var flag_is_all: boolean = false;
        this.categoriesService.getCategories(lang, this.type).subscribe(value => {
            this.categoriesList = value.data;
            const selected_category = this.categoriesList.find(item => item.slug == this.slug);
            if(selected_category != undefined){
                this.selected_category = selected_category;
                this.slug_en = this.selected_category.slug_en;
            } else {
                flag_is_all = true;
                this.selected_category = this.categoriesList[0];
            }
            this.updateMetaTags();
            id = this.selected_category.id;
            id_to_empty = this.selected_category.id;
            if(flag_is_all){
                id_to_empty = null;
            }
            this.ourStoriesService.getStories(lang, this.type, id_to_empty).subscribe(value => this.storiesList = value.data);
            this.testimonialsService.getTestimonials(lang, this.type, id_to_empty).subscribe(value => this.testimonialsList = value.data);
            this.factAndStatisticsService.getFactsAndStatistics(lang, this.type, id).subscribe(value => this.factsList = value.data);
            this.ourPartnersService.getPartners(lang, this.type, id_to_empty).subscribe(value => this.partnersList = value.data);
            this.projectsService.getProjects(lang, this.type, id_to_empty).subscribe(value => {
                this.projectsList = value?.data ?? [];
            });
            this.quickContributionService.getContribution(lang, "2", id).subscribe(value => {
                this.quickContribution = null;
                if(value){
                    if(value.data.length > 0){
                        this.quickContribution = value.data[0];
                    }
                }
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
            content: window.location.href
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
        this.quickContribution = null;
    }
      
}

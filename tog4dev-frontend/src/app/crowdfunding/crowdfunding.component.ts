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
import { ProjectsService } from 'app/shared/services/projects/projects.service';
import { ProjectItem } from 'app/shared/components/project-list-item/types/project-list-item.types';
import {QuickContributionService} from "../shared/services/quick-contribution/quick-contribution.service";
import {ActivatedRoute, Router} from "@angular/router";
import {NgClass, NgIf} from "@angular/common";
import {TranslatePipe} from "@ngx-translate/core";
import {Contribution} from '../shared/services/types/QuickContributions.types'
import {Meta} from "@angular/platform-browser";


@Component({
    selector: 'app-crowdfunding',
    imports: [
        OurPartnersComponent,
        OurStoriesComponent,
        TestimonialsComponent,
        HeroSectionComponent,
        FactsAndStatisticsComponent,
        CategoriesListComponent,
        ProjectListItemComponent,
        NgIf,
        TranslatePipe,
        NgClass
    ],
    templateUrl: './crowdfunding.component.html',
    styleUrl: './crowdfunding.component.scss'
})
export class CrowdfundingComponent implements OnInit, OnDestroy {
  constructor(public storageService: StorageService,
              public ourStoriesService: OurStoriesService,
              public testimonialsService: TestimonialsService,
              public factAndStatisticsService: FactAndStatisticsService,
              public categoriesService: CategoriesService,
              public ourPartnersService: OurPartnersService,
              public metaService: Meta,
              public projectsService: ProjectsService,
              public quickContributionService: QuickContributionService,
              private router: Router,
              private route: ActivatedRoute) {}

  type: string = 'crowdfunding';
  id: number | null = null;
  slug: string | null = null;
  slug_en: string | null = null;
  selected_category!: Category;
  destory$ = new Subject<void>;
  storiesList: Story[] = [];
  testimonialsList: Testimonial[] = [];
  categoriesList: Category[] = [];
  factsList: Fact[] = [];
  partnersList: Partner[] = [];
  projectsList: ProjectItem[] = [];
  quickContribution: any;
  lang: string = '';
  activeCategory: 'all' | 'current' | 'completed' = 'all';
  private routeSub!: Subscription;

  
    /**
   * Angular afterViewInit lifecycle method
   */
    ngOnInit(): void {
        this.lang = this.storageService.siteLanguage$.value;
        this.route.paramMap.subscribe(params => {
            this.slug = params.get('category_slug');
            this.fetchData();
        });
    }

  setActiveCategory(category: 'all' | 'current' | 'completed') {
      this.activeCategory = category;
  }

  fetchData() {
    this.storageService.siteLanguage$.pipe(takeUntil(this.destory$)).subscribe(lang => {
        if(lang != this.lang && this.slug != null){
            this.lang == lang;
            if(lang == "ar"){
                this.router.navigate(["ar/التمويل-الجماعي", this.selected_category.slug_ar]);
            } else {
                this.router.navigate(["en/crowdfunding", this.selected_category.slug_en]);
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
            this.projectsService.getProjects(lang, this.type, id_to_empty).subscribe(value => this.projectsList = value?.data ?? []);
            this.quickContributionService.getContribution(lang, "3", id).subscribe(value => {
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
}

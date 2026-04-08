import { Component, OnDestroy, OnInit } from '@angular/core';

import {Subject, Subscription, takeUntil} from 'rxjs';

import { OurStoriesService } from "../shared/components/our-stories/services/our-stories.service";
import { TestimonialsService } from "../shared/components/testimonials/services/testimonials.service";
import { StorageService } from 'app/core/storage/storage.service';

import { HeroSectionComponent } from "./components/hero-section/hero-section.component";
import { OurPartnersComponent } from "../shared/components/our-partners/our-partners.component";
import { TestimonialsComponent } from "../shared/components/testimonials/testimonials.component";
import { ProjectsListWithFilterComponent } from "../home/components/projects-list-with-filter/projects-list-with-filter.component";

import { Story } from "../shared/components/our-stories/types/our-stories.types";
import { Testimonial } from "../shared/components/testimonials/types/testimonial.types";
import {ContactUsFormComponent} from "../shared/components/contact-us-form/contact-us-form.component";
import {OurPartnersService} from "../shared/components/our-partners/services/our-partners.service";
import {Partner} from "../shared/components/our-partners/types/our-partners.types";
import {Category} from "../shared/services/types/categories.types";
import {CategoriesService} from "../shared/services/categories/categories.service";
import {CategoriesListComponent} from "../shared/components/categories-list/categories-list.component";
import {ProjectListItemComponent} from "../shared/components/project-list-item/project-list-item.component";
import { ProjectsService } from 'app/shared/services/projects/projects.service';
import { ProjectItem } from 'app/shared/components/project-list-item/types/project-list-item.types';
import {ActivatedRoute, Router} from "@angular/router";
import {NgIf} from "@angular/common";
import {TranslatePipe} from "@ngx-translate/core";
import {Meta} from "@angular/platform-browser";


@Component({
    selector: 'app-organization',
    imports: [
        OurPartnersComponent,
        ProjectsListWithFilterComponent,
        TestimonialsComponent,
        HeroSectionComponent,
        ContactUsFormComponent,
        CategoriesListComponent,
        ProjectListItemComponent,
        NgIf,
        TranslatePipe,
    ],
    templateUrl: './organization.component.html',
    styleUrl: './organization.component.scss'
})
export class OrganizationComponent implements OnInit, OnDestroy {

  constructor(public storageService: StorageService,
              public ourStoriesService: OurStoriesService,
              public categoriesService: CategoriesService,
              public testimonialsService: TestimonialsService,
              public ourPartnersService: OurPartnersService,
              public metaService: Meta,
              public projectsService: ProjectsService,
              private router: Router,
              private route: ActivatedRoute) {}

  type: string = 'organization';
  id: number | null = null;
  slug: string | null = null;
  slug_en: string | null = null;
  selected_category!: Category;
  destory$ = new Subject<void>;
  storiesList: Story[] = [];
  testimonialsList: Testimonial[] = [];
  partnersList: Partner[] = [];
  categoriesList: Category[] = [];
  projectsList: ProjectItem[] = [];
  lang: string = '';
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

  fetchData(){
    this.storageService.siteLanguage$.pipe(takeUntil(this.destory$)).subscribe(lang => {
      if(lang != this.lang && this.slug != null){
        this.lang == lang;
        if(lang == "ar"){
          this.router.navigate(["ar/مشاريع-المنظمات", this.selected_category.slug_ar]);
        } else {
          this.router.navigate(["en/organizations-projects", this.selected_category.slug_en]);
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
        this.ourPartnersService.getPartners(lang, this.type, id_to_empty).subscribe(value => this.partnersList = value.data);
        this.projectsService.getProjects(lang, this.type, id_to_empty).subscribe(value => this.projectsList = value?.data ?? []);
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
}

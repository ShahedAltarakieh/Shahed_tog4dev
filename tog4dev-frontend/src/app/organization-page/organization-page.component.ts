import { Component, OnDestroy, OnInit } from '@angular/core';
import { StorageService } from 'app/core/storage/storage.service';
import { BreadcrumbComponent } from 'app/shared/components/breadcrumb/breadcrumb.component';

import { Breadcrumb } from 'app/shared/components/breadcrumb/types/breadcrumb.types';
import { FactsAndStatisticsComponent } from 'app/shared/components/facts-and-statistics/facts-and-statistics.component';
import { FactAndStatisticsService } from 'app/shared/components/facts-and-statistics/services/fact-and-statistics.service';
import { Fact } from 'app/shared/components/facts-and-statistics/types/fact-and-statistics.types';
import { OurPartnersComponent } from 'app/shared/components/our-partners/our-partners.component';
import { OurPartnersService } from 'app/shared/components/our-partners/services/our-partners.service';
import { Partner } from 'app/shared/components/our-partners/types/our-partners.types';
import { ProjectSliderComponent } from 'app/shared/components/project-slider/project-slider.component';
import { TestimonialsService } from 'app/shared/components/testimonials/services/testimonials.service';
import { TestimonialsComponent } from 'app/shared/components/testimonials/testimonials.component';
import { Testimonial } from 'app/shared/components/testimonials/types/testimonial.types';
import { Category } from 'app/shared/services/types/categories.types';
import { Subject, takeUntil } from 'rxjs';
import {ActivatedRoute, Router} from "@angular/router";
import {ProjectsService} from "../shared/services/projects/projects.service";
import {ProjectItem} from "../shared/components/project-list-item/types/project-list-item.types";
import {NgIf} from "@angular/common";
import {TranslatePipe} from "@ngx-translate/core";
import {Meta} from "@angular/platform-browser";
import { MetaPixelService } from 'app/shared/services/meta-pixel-service/meta-pixel.service';

@Component({
    selector: 'app-organization-page',
    imports: [
        BreadcrumbComponent,
        ProjectSliderComponent,
        FactsAndStatisticsComponent,
        TestimonialsComponent,
        OurPartnersComponent,
        NgIf,
        TranslatePipe
    ],
    templateUrl: './organization-page.component.html',
    styleUrl: './organization-page.component.scss'
})
export class OrganizationPageComponent implements OnInit, OnDestroy {
  breadcrumb: Breadcrumb[] = [];

  factsList: Fact[] = [];
  type: string = 'organization';
  destory$ = new Subject<void>;
  testimonialsList: Testimonial[] = [];
  partnersList: Partner[] = [];
  id: number | null = null;
  lang: string = '';
  category_slug: string | null = '';
  item_slug: string | null = '';
  project!: ProjectItem;
  constructor(public storageService: StorageService,
    public projectService: ProjectsService,
    public factAndStatisticsService: FactAndStatisticsService,
    public testimonialsService: TestimonialsService,
    public ourPartnersService: OurPartnersService,
    private pixel: MetaPixelService,
    public metaService: Meta,
    private route: ActivatedRoute,
    private router: Router,
  ) {}


  /**
   * Angular afterViewInit lifecycle method
   */
  ngOnInit(): void {
    this.lang = this.storageService.siteLanguage$.value;
    this.route.paramMap.subscribe(params => {
      this.category_slug = params.get('category_slug');
      this.item_slug = params.get('slug');
      this.fetchData();
    });
  }

  fetchData(){
    this.storageService.siteLanguage$.pipe(takeUntil(this.destory$)).subscribe(lang => {
      
      
      if(lang != this.lang && this.item_slug != null){
        this.lang == lang;
        if(lang == "ar"){
          this.router.navigate(["ar/مشاريع-المنظمات", this.project.category.slug_ar ,this.project.slug_ar]);
        } else {
          this.router.navigate(["en/organizations-projects", this.project.category.slug_en ,this.project.slug_en]);
        }
        return;
      }
      this.projectService.getProject(lang, this.item_slug).subscribe(value => {
        if(value){
          this.project = value.data;
          var command = '';
          if(lang == "ar"){
            command = "/ar/مشاريع-المنظمات/";
          } else {
            command = "/en/organizations-projects/";
          }
          if(this.project){
            this.breadcrumb = [];
            this.breadcrumb.push({
              link: command,
              title: "B2B"
            });
            this.breadcrumb.push({
              link: command + this.project.category.slug,
              title: this.project.category.title
            });
            this.breadcrumb.push({
              link: command + this.project.category.slug + "/" + this.project.slug,
              title: this.project.title
            });
          }

          this.factAndStatisticsService.getFactsAndStatistics(lang, this.type, this.project.category.id).subscribe(value => this.factsList = value.data);
          this.testimonialsService.getTestimonials(lang, this.type, this.project.category.id).subscribe(value => this.testimonialsList = value.data);
          this.ourPartnersService.getPartners(lang, this.type, this.id).subscribe(value => this.partnersList = value.data);

          this.pixel.trackViewContentProduct(
            {
              productId: this.project.id,
              name: this.project.title,
              category: this.project.category.title,
              price: 0
            },
            {
              type: "B2B Projects"
            }
          );
          this.updateMetaTags();
        }
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
      content: this.project.description
    });
    // Update Open Graph meta tags
    this.metaService.updateTag({
      property: 'og:title',
      content: this.project.title
    });
    this.metaService.updateTag({
      property: 'og:description',
      content: this.project.description
    });
    this.metaService.updateTag({
      property: 'og:image',
      content: this.project.image
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
      content: this.project.image
    });
    this.metaService.updateTag({
      name: 'twitter:title',
      content: this.project.title
    });
    this.metaService.updateTag({
      name: 'twitter:description',
      content: this.project.description
    });
    this.metaService.updateTag({
      name: 'twitter:image',
      content: this.project.image
    });
  }
}

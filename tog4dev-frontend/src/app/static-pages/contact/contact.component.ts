import { Component, OnInit } from '@angular/core';
import { FormGroup, FormBuilder, Validators, ReactiveFormsModule } from '@angular/forms';
import { Meta } from '@angular/platform-browser';

import { TranslateModule } from '@ngx-translate/core';
import { StorageService } from 'app/core/storage/storage.service';

import { ContactUsFormComponent } from 'app/shared/components/contact-us-form/contact-us-form.component';
import { Subject, takeUntil } from 'rxjs';

@Component({
    selector: 'app-contact',
    imports: [ReactiveFormsModule, TranslateModule, ContactUsFormComponent],
    templateUrl: './contact.component.html',
    styleUrl: './contact.component.scss'
})
export class ContactComponent implements OnInit{
  contactForm: FormGroup;
  isSubmitted: boolean = false;
  isLoading: boolean = false;
  destroy$ = new Subject<void>;

  constructor(private fb: FormBuilder, 
    private storageService: StorageService,
    public metaService: Meta) {
    this.contactForm = this.fb.group({
      name: ['', Validators.required],
      email: ['', [Validators.required, Validators.email]],
      message: ['', Validators.required],
    });
  }

  ngOnInit(): void {
    this.storageService.siteLanguage$.pipe(takeUntil(this.destroy$)).subscribe(lang => {
        this.updateMetaTags();
    });
  }

  /**
   * Submit contact-us
   * 
   * @returns { void }
   */
  submitForm(): void {
    if (this.contactForm.invalid) {
      return;
    }

    this.isLoading = true;

    setTimeout(() => {
      this.isLoading = false;
      this.isSubmitted = true;

      this.contactForm.reset();
    }, 2000); // Simulates a network request
  }

  updateMetaTags(): void {
    // Update standard meta tags
    this.metaService.updateTag({
      name: 'description',
      content: (this.storageService.siteLanguage$.value == "ar") ? "نقدّم حلولًا ذكية تخدم الإنسان، وتُمكّن المؤسسات من تنفيذ مشاريع تنموية بجودة وشفافية واستدامة حول العالم" : "Smart solutions for people and institutions to build impactful, transparent, and lasting development worldwide."
    });
    // Update Open Graph meta tags
    this.metaService.updateTag({
      property: 'og:title',
      content: "Together For Development | معاً للتنمية"
    });
    this.metaService.updateTag({
      property: 'og:description',
      content: (this.storageService.siteLanguage$.value == "ar") ? "نقدّم حلولًا ذكية تخدم الإنسان، وتُمكّن المؤسسات من تنفيذ مشاريع تنموية بجودة وشفافية واستدامة حول العالم" : "Smart solutions for people and institutions to build impactful, transparent, and lasting development worldwide."
    });
    this.metaService.updateTag({
      property: 'og:image',
      content: "https://tog4dev.com/app/assets/images/shared/logo.png"
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
      content: "https://tog4dev.com/app/assets/images/shared/logo.png"
    });
    this.metaService.updateTag({
      name: 'twitter:title',
      content: "Together For Development | معاً للتنمية"
    });
    this.metaService.updateTag({
      name: 'twitter:description',
      content: (this.storageService.siteLanguage$.value == "ar") ? "نقدّم حلولًا ذكية تخدم الإنسان، وتُمكّن المؤسسات من تنفيذ مشاريع تنموية بجودة وشفافية واستدامة حول العالم" : "Smart solutions for people and institutions to build impactful, transparent, and lasting development worldwide."
    });
    this.metaService.updateTag({
      name: 'twitter:image',
      content: "https://tog4dev.com/app/assets/images/shared/logo.png"
    });
  }
}

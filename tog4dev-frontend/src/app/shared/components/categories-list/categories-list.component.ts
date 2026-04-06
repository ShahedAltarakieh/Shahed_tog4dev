import {Component, EventEmitter, Input, OnChanges, OnDestroy, Output, SimpleChanges} from '@angular/core';
import {NgClass} from "@angular/common";
import {Category} from "../../services/types/categories.types";
import { Router } from '@angular/router';
import {StorageService} from "../../../core/storage/storage.service";
import {Subject, Subscription} from "rxjs";

@Component({
    selector: 'app-categories-list',
    imports: [
        NgClass
    ],
    templateUrl: './categories-list.component.html',
    styleUrl: './categories-list.component.scss'
})
export class CategoriesListComponent implements OnChanges, OnDestroy{
  @Input({ required: true }) list: Category[] = [];

  activeCategory: string | undefined;
  @Output() category_id = new EventEmitter<Category>(); // Emits the updated list
  @Input() selected_slug!: string | null;
  @Input() type!: string;
  private routeSub!: Subscription;
  destory$ = new Subject<void>;

  constructor(private router: Router,
              public storageService: StorageService) {}

  ngOnChanges(changes: SimpleChanges): void {
    if (changes['list'] && changes['list'].currentValue) {
      const list = changes['list'].currentValue;
      if (list.length > 0) {
        if(this.selected_slug != null){
          this.activeCategory = this.selected_slug;
        } else{
          this.activeCategory = list[0].slug_en;
        }
      }
    }
  }

  /**
   * Set Active category to filter the projects (TODO)
   *
   * @param  Category
   */
  setActiveCategory(category: Category) {
    this.activeCategory = category.slug_en;
    var commands = null;
    var commands_ar = null;
    switch (this.type){
      case 'projects':
        commands = 'en/individual-projects';
        commands_ar = 'ar/المشاريع-الفردية';
      break;
      case 'organization':
        commands = 'en/organizations-projects';
        commands_ar = 'ar/مشاريع-المنظمات';
        break;
      case 'crowdfunding':
        commands = 'en/crowdfunding';
        commands_ar = 'ar/التمويل-الجماعي';
        break;
    }
    if(category.is_all_option){
      this.router.navigate([this.storageService.siteLanguage$.value === 'ar' ? commands_ar : commands]);
    } else {
      this.router.navigate([this.storageService.siteLanguage$.value === 'ar' ? commands_ar : commands, category.slug]);
    }
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
}

import { Component, Input, computed, signal } from '@angular/core';
import { NgClass } from '@angular/common';

import { ProjectListItemComponent } from 'app/shared/components/project-list-item/project-list-item.component';
import { ProjectItem } from 'app/shared/components/project-list-item/types/project-list-item.types';
import { TranslatePipe } from '@ngx-translate/core';

@Component({
    selector: 'app-projects-list-with-filter',
    imports: [
        NgClass,
        TranslatePipe,
        ProjectListItemComponent
    ],
    templateUrl: './projects-list-with-filter.component.html',
    styleUrl: './projects-list-with-filter.component.scss'
})
export class ProjectsListWithFilterComponent {
  @Input({ required: true }) projectsList: ProjectItem[] = [];

  activeCategory: 'all' | 'crowdfunding' | 'projects' = 'all';
  readonly initialVisible = 6;
  readonly step = 6;
  visibleCount = signal<number>(this.initialVisible);

  get filteredProjects(): ProjectItem[] {
    if (this.activeCategory === 'all') return this.projectsList;
    if (this.activeCategory === 'projects') {
      return this.projectsList.filter(i => i.type_id != 3);
    }
    if (this.activeCategory === 'crowdfunding') {
      return this.projectsList.filter(i => i.type_id != 2);
    }
    return this.projectsList;
  }

  get visibleProjects(): ProjectItem[] {
    return this.filteredProjects.slice(0, this.visibleCount());
  }

  get hasMore(): boolean {
    return this.visibleCount() < this.filteredProjects.length;
  }

  get canShowLess(): boolean {
    return this.visibleCount() > this.initialVisible && this.filteredProjects.length > this.initialVisible;
  }

  setActiveCategory(category: 'all' | 'crowdfunding' | 'projects') {
    this.activeCategory = category;
    this.visibleCount.set(this.initialVisible);
  }

  loadMore(): void {
    this.visibleCount.update(v => v + this.step);
  }

  showLess(): void {
    this.visibleCount.set(this.initialVisible);
  }
}

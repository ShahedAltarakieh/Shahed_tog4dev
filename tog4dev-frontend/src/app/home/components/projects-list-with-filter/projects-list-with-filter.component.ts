import { Component, Input } from '@angular/core';
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

  /**
   * Set Active category to filter the projects (TODO)
   * 
   * @param  category 
   */
  setActiveCategory(category: 'all' | 'crowdfunding' | 'projects') {
    this.activeCategory = category;
  }
}

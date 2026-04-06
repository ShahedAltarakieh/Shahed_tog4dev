import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ProjectsListWithFilterComponent } from './projects-list-with-filter.component';

describe('ProjectsListWithFilterComponent', () => {
  let component: ProjectsListWithFilterComponent;
  let fixture: ComponentFixture<ProjectsListWithFilterComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ProjectsListWithFilterComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(ProjectsListWithFilterComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

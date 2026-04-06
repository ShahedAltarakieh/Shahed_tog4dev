import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ProjectSliderComponent } from './project-slider.component';

describe('ProjectSliderComponent', () => {
  let component: ProjectSliderComponent;
  let fixture: ComponentFixture<ProjectSliderComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ProjectSliderComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(ProjectSliderComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

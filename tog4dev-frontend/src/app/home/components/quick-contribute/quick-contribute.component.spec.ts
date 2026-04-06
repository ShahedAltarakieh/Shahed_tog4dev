import { ComponentFixture, TestBed } from '@angular/core/testing';

import { QuickContributionComponent } from './quick-contribute.component';

describe('QuickContributionComponent', () => {
  let component: QuickContributionComponent;
  let fixture: ComponentFixture<QuickContributionComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [QuickContributionComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(QuickContributionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CrowdfundingPageComponent } from './crowdfunding-page.component';

describe('CrowdfundingPageComponent', () => {
  let component: CrowdfundingPageComponent;
  let fixture: ComponentFixture<CrowdfundingPageComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [CrowdfundingPageComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(CrowdfundingPageComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

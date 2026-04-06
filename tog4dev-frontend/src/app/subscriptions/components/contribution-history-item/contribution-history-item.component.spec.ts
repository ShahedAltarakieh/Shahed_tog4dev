import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ContributionHistoryItemComponent } from './contribution-history-item.component';

describe('ContributionHistoryItemComponent', () => {
  let component: ContributionHistoryItemComponent;
  let fixture: ComponentFixture<ContributionHistoryItemComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ContributionHistoryItemComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(ContributionHistoryItemComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

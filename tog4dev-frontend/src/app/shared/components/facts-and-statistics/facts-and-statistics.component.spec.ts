import { ComponentFixture, TestBed } from '@angular/core/testing';

import { FactsAndStatisticsComponent } from './facts-and-statistics.component';

describe('FactsAndStatisticsComponent', () => {
  let component: FactsAndStatisticsComponent;
  let fixture: ComponentFixture<FactsAndStatisticsComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [FactsAndStatisticsComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(FactsAndStatisticsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

import { TestBed } from '@angular/core/testing';

import { FactAndStatisticsService } from './fact-and-statistics.service';

describe('FactAndStatisticsService', () => {
  let service: FactAndStatisticsService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(FactAndStatisticsService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});

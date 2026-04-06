import { TestBed } from '@angular/core/testing';

import { QuickContributionService } from './quick-contribution.service';

describe('QuickContributionService', () => {
  let service: QuickContributionService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(QuickContributionService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});

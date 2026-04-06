import { TestBed } from '@angular/core/testing';

import { MyDonationService } from './my-donation.service';

describe('MyDonationService', () => {
  let service: MyDonationService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(MyDonationService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});

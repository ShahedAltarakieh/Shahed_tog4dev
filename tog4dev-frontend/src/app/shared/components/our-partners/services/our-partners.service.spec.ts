import { TestBed } from '@angular/core/testing';

import { OurPartnersService } from './our-partners.service';

describe('OurPartnersService', () => {
  let service: OurPartnersService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(OurPartnersService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});

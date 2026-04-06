import { TestBed } from '@angular/core/testing';

import { PhoneCountrySanitizerService } from './phone-country-sanitizer.service';

describe('PhoneCountrySanitizerService', () => {
  let service: PhoneCountrySanitizerService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(PhoneCountrySanitizerService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});

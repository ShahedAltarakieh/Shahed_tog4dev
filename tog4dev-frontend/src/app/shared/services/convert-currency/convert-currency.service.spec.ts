import { TestBed } from '@angular/core/testing';

import { ConvertCurrencyService } from './convert-currency.service';

describe('ConvertCurrencyService', () => {
  let service: ConvertCurrencyService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(ConvertCurrencyService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});

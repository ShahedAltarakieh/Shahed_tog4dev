import { TestBed } from '@angular/core/testing';

import { HttpsRedirectService } from './https-redirect.service';

describe('HttpsRedirectService', () => {
  let service: HttpsRedirectService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(HttpsRedirectService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});

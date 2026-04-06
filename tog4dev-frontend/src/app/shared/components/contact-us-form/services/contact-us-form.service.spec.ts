import { TestBed } from '@angular/core/testing';

import { ContactUsFormService } from './contact-us-form.service';

describe('ContactUsFormService', () => {
  let service: ContactUsFormService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(ContactUsFormService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});

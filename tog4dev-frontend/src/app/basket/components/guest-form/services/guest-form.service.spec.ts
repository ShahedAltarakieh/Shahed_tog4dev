import { TestBed } from '@angular/core/testing';

import { GuestFormService } from "./guest-form.service";

describe('GuestFormService', () => {
  let service: GuestFormService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(GuestFormService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});

import { TestBed } from '@angular/core/testing';
import { CanActivateFn } from '@angular/router';

import { redirectNotLoggedUserGuard } from './redirect-not-logged-user.guard';

describe('redirectNotLoggedUserGuard', () => {
  const executeGuard: CanActivateFn = (...guardParameters) => 
      TestBed.runInInjectionContext(() => redirectNotLoggedUserGuard(...guardParameters));

  beforeEach(() => {
    TestBed.configureTestingModule({});
  });

  it('should be created', () => {
    expect(executeGuard).toBeTruthy();
  });
});

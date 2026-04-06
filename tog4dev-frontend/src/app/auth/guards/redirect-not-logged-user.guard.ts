import { CanActivateFn, Router } from '@angular/router';
import { inject } from '@angular/core';

import { AuthService } from '../services/auth.service';

export const redirectNotLoggedUserGuard: CanActivateFn = (route, state) => {
  const router = inject(Router);
  const authService = inject(AuthService);

  if (authService.loggedInUser) {
    router.navigate(['/']);
    return false;
  } else {
    return true;
  }
};

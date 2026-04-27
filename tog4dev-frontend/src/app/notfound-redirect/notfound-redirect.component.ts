import { Component } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';


@Component({
    selector: 'app-notfound-redirect',
    imports: [
    ],
    templateUrl: './notfound-redirect.component.html',
    styleUrl: './notfound-redirect.component.scss'
})
export class NotfoundRedirectComponent {

  constructor(private router: Router, private route: ActivatedRoute) {
    const queryParams = { ...this.route.snapshot.queryParams };
    if (Object.keys(queryParams).length > 0) {
      // redirect keeping query params
      this.router.navigate(['/en'], { queryParams });
    } else {
      // redirect without query params
      this.router.navigate(['/en']);
    }
  }
}

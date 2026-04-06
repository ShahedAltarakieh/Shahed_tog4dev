import { Component, OnDestroy, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { ShortlinkService } from 'app/shared/services/shortlink/shortlink-service.service';


@Component({
    selector: 'app-shortlink-redirect',
    imports: [
    ],
    templateUrl: './shortlink-redirect.component.html',
    styleUrl: './shortlink-redirect.component.scss'
})
export class ShortlinkRedirectComponent implements OnInit {
  constructor(private route: ActivatedRoute, private router: Router, private shortLink: ShortlinkService) {}

  ngOnInit(): void {
    this.route.paramMap.subscribe(params => {
      const code = params.get('code');

      if (code) {
        this.shortLink.getUrl(code).subscribe({
            next: (response: any) => {
                if(response && response.original_url){
                    window.location.href = response.original_url;
                } else {
                    this.router.navigate(['/']); // fallback if not found
                }                
            },
            error: (err: any) => {
                this.router.navigate(['/']);
            },
        });
      }
    });

  }
}

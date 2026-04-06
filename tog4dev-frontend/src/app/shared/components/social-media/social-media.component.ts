import { Component } from '@angular/core';
import { StorageService } from 'app/core/storage/storage.service';

@Component({
    selector: 'app-social-media',
    imports: [],
    templateUrl: './social-media.component.html',
    styleUrl: './social-media.component.scss'
})
export class SocialMediaComponent {
  constructor(public storageService: StorageService) {}
} 

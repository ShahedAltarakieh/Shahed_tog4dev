import { Component } from '@angular/core';
import { StorageService } from 'app/core/storage/storage.service';
import {TranslatePipe} from "@ngx-translate/core";

@Component({
    selector: 'app-whats-app',
    imports: [
        TranslatePipe
    ],
    templateUrl: './whats-app.component.html',
    styleUrl: './whats-app.component.scss'
})
export class WhatsAppComponent {
  constructor(public storageService: StorageService) {}
} 

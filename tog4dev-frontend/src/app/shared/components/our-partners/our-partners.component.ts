import {Component, Input} from '@angular/core';
import {Story} from "../our-stories/types/our-stories.types";
import {Partner} from "./types/our-partners.types";
import {TranslatePipe} from "@ngx-translate/core";

@Component({
    selector: 'app-our-partners',
    imports: [
        TranslatePipe
    ],
    templateUrl: './our-partners.component.html',
    styleUrl: './our-partners.component.scss'
})
export class OurPartnersComponent {
  @Input({ required: true }) partnersList: Partner[] = [];

}

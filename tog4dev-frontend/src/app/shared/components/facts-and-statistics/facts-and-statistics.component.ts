import {Component, Input} from '@angular/core';
import {Testimonial} from "../testimonials/types/testimonial.types";
import {Fact} from "./types/fact-and-statistics.types";
import {TranslatePipe} from "@ngx-translate/core";

@Component({
    selector: 'app-facts-and-statistics',
    imports: [
        TranslatePipe
    ],
    templateUrl: './facts-and-statistics.component.html',
    styleUrl: './facts-and-statistics.component.scss'
})
export class FactsAndStatisticsComponent {
  @Input({ required: true }) factsList: Fact[] = [];

}

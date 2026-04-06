import {Component, Input} from '@angular/core';

@Component({
    selector: 'app-contribution-history-item',
    imports: [],
    templateUrl: './contribution-history-item.component.html',
    styleUrl: './contribution-history-item.component.scss'
})
export class ContributionHistoryItemComponent {
  @Input() item!: any;

}

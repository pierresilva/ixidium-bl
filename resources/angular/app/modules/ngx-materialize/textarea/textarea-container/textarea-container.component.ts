import {Component, ElementRef, Input, ViewChild} from '@angular/core';

declare var M: any;

@Component({
  selector: 'mz-textarea-container',
  templateUrl: './textarea-container.component.html',
  styleUrls: ['./textarea-container.component.scss'],
})
export class MzTextareaContainerComponent {
  @Input() inline: boolean;
}

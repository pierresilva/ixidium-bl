import {Component, ElementRef, Input, OnInit, Renderer} from '@angular/core';

@Component({
  selector: 'mz-datepicker-container',
  templateUrl: './datepicker-container.component.html',
  styleUrls: ['./datepicker-container.component.scss'],
})
export class MzDatepickerContainerComponent implements OnInit {
  @Input() inline: boolean;
  @Input() class: any;

  constructor(
    private elementRef: ElementRef,
    private renderer: Renderer,
  ) {}

  ngOnInit(): void {
    /*console.log($(this.elementRef.nativeElement).attr('class'));
    $(this.elementRef.nativeElement).attr('class', '');*/
  }
}

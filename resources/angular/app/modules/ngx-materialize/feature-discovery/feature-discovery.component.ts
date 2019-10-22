import { AfterViewInit, Component, ElementRef, HostBinding, Input } from '@angular/core';
declare var $: any;

@Component({
  selector: 'mz-feature-discovery',
  templateUrl: './feature-discovery.component.html',
  styleUrls: ['./feature-discovery.component.scss'],
})
export class MzFeatureDiscoveryComponent implements AfterViewInit {
  @HostBinding('class.tap-target')
  targetClass = true;

  @HostBinding('attr.data-target')
  @Input()
  targetId: string;

  private target: any;

  constructor(
    private elementRef: ElementRef,
  ) { }

  ngAfterViewInit() {
    this.target = $(this.elementRef.nativeElement).tapTarget();
  }

  close() {
    this.target.tapTarget('close');
  }

  open() {
    this.target.tapTarget('open');
  }
}

import { AfterViewInit, Directive, ElementRef, Input, OnChanges, OnDestroy, OnInit, Renderer, SimpleChanges } from '@angular/core';

declare var $: any;

@Directive({
  selector: '[mzTooltip], [mz-tooltip]',
})
export class MzTooltipDirective implements OnInit, AfterViewInit, OnChanges, OnDestroy {
  @Input() delay: number;
  @Input() position: string;
  @Input() tooltip: string;
  @Input() margin: number;

  targetElement: any;

  constructor(
    private elementRef: ElementRef,
    private renderer: Renderer,
  ) { }

  ngOnInit() {
    this.initElements();
  }

  ngAfterViewInit() {
    if (this.elementRef.nativeElement.getAttribute('type') === 'checkbox') {
      this.targetElement = $(this.elementRef.nativeElement).next('label');
    }

    this.initTooltip();
  }

  ngOnChanges(changes: SimpleChanges) {
    if (this.targetElement) {
      this.initTooltip();
    }
  }

  ngOnDestroy() {
    this.renderer.invokeElementMethod(this.targetElement, 'tooltip', ['destroy']);
  }

  initElements() {
    this.targetElement = $(this.elementRef.nativeElement);
  }

  initTooltip() {
    const tooltipOptions: any = {
      enterDelay: isNaN(this.delay) || this.delay == null ? 0 : this.delay,
      html: this.tooltip,
      position: this.position || 'bottom',
      margin: isNaN(this.margin) || this.margin == null ? 0 : this.margin,
    };

    this.renderer.invokeElementMethod(this.targetElement, 'tooltip', [tooltipOptions]);
  }
}

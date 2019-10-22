import {AfterViewInit, Directive, ElementRef, EventEmitter, Input, OnDestroy, OnInit, Output} from '@angular/core';

declare const M: any;

@Directive({
  selector: '[mzCarousel]'
})
export class MzCarouselDirective implements OnInit, AfterViewInit, OnDestroy {

  @Input('mzCarousel') mzCarousel: object;
  @Output() mzInstance = new EventEmitter();
  options = {};
  instances: any;

  constructor(
    private element: ElementRef
  ) { }

  ngOnInit(): void {

  }

  ngAfterViewInit(): void {
    setTimeout(() => {
      Object.assign(this.options, this.mzCarousel);
      this.instances = M.Carousel.init(this.element.nativeElement, this.options);
      this.mzInstance.emit(this.instances);
    }, 0);
  }

  ngOnDestroy(): void {
    if (this.instances) {
      this.instances.destroy();
    }
  }
}

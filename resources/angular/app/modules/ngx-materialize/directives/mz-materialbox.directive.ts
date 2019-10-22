import {Directive, ElementRef, EventEmitter, Input, OnDestroy, OnInit, Output} from '@angular/core';

declare const M: any;

@Directive({
  selector: '[mzMaterialbox]'
})
export class MzMaterialboxDirective implements OnInit, OnDestroy {

  @Input('mzMaterialbox') mzMaterialbox: object;
  @Output() mzInstance = new EventEmitter();
  options = {};
  instances: any;

  constructor(
    private element: ElementRef
  ) { }

  ngOnInit() {
    setTimeout(() => {
      Object.assign(this.options, this.mzMaterialbox);
      this.instances = M.Materialbox.init(this.element.nativeElement, this.options);
      this.mzInstance.emit(this.instances);
    }, 0);
  }

  ngOnDestroy(): void {
    if (this.instances) {
      this.instances.destroy();
    }
  }

}

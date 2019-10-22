import {Directive, ElementRef, EventEmitter, Input, OnDestroy, OnInit, Output} from '@angular/core';

declare const M: any;

@Directive({
  selector: '[mzTabs], [mz-tabs]'
})
export class MzTabsDirective implements OnInit, OnDestroy {

  @Input('mzTabs') mzTabs: object;
  @Output() mzInstance = new EventEmitter();
  options = {};
  instances: any;

  constructor(
    private element: ElementRef
  ) { }

  ngOnInit() {
    setTimeout(() => {
      Object.assign(this.options, this.mzTabs);
      this.instances = M.Tabs.init(this.element.nativeElement, this.options);
      this.mzInstance.emit(this.instances);
    }, 0);
  }

  ngOnDestroy(): void {
    if (this.instances) {
      this.instances.destroy();
    }
  }

}

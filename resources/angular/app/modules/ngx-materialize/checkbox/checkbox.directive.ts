import { Directive, ElementRef, HostBinding, Input, OnInit, Renderer } from '@angular/core';

import { HandlePropChanges } from '../shared/index';

declare var $: any;

@Directive({
  selector: 'input[mzCheckbox], input[mz-checkbox]',
})
export class MzCheckboxDirective extends HandlePropChanges implements OnInit {
  // native properties
  @HostBinding() @Input() id: string;

  // directive properties
  @Input() filledIn: boolean;
  @Input() label: string;

  checkboxElement: any;
  checkboxContainerElement: any;
  labelElement: any;

  constructor(private elementRef: ElementRef, private renderer: Renderer) {
    super();
  }

  ngOnInit() {
    this.initHandlers();
    this.initElements();
    this.handleProperties();
  }

  initHandlers() {
    this.handlers = {
      filledIn: () => this.handleFilledIn(),
      label: () => this.handleLabel(),
    };
  }

  initElements() {
    this.checkboxElement = $(this.elementRef.nativeElement);
    this.checkboxContainerElement = $(this.elementRef.nativeElement).parent('.checkbox-field');
  }

  handleProperties() {
    if (this.checkboxContainerElement.length === 0) {
      console.error('Input with mz-checkbox directive must be placed inside a [mz-checkbox-container] tag', this.checkboxElement);
      return;
    }

    super.executePropHandlers();
  }

  handleLabel() {
    const spanElement = document.createElement('span');
    spanElement.innerText = this.label;
    this.renderer.invokeElementMethod(this.checkboxElement, 'after', [spanElement]);
  }

  handleFilledIn() {
    this.renderer.setElementClass(this.checkboxElement[0], 'filled-in', this.filledIn);
  }
}

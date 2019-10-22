import { Directive, ElementRef, HostBinding, Input, OnInit, Renderer } from '@angular/core';

import { HandlePropChanges } from '../shared/index';

declare var $: any;

@Directive({
  selector: 'input[mzRadioButton], input[mz-radio-button]',
})
export class MzRadioButtonDirective extends HandlePropChanges implements OnInit {
  // native properties
  @HostBinding() @Input() id: string;

  // directive properties
  @Input() label: string;
  @Input() withGap: boolean;

  inputElement: any;
  inputContainerElement: any;
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
      label: () => this.handleLabel(),
      withGap: () => this.handleWithGap(),
    };
  }

  initElements() {
    this.inputElement = $(this.elementRef.nativeElement);
    this.inputContainerElement = $(this.elementRef.nativeElement).parent('.radio-button-field');
    // this.labelElement = this.createLabelElement();
  }

  createLabelElement() {
    const labelElement = document.createElement('label');
    labelElement.setAttribute('for', this.id);

    this.renderer.invokeElementMethod(this.inputElement, 'after', [labelElement]);

    return $(labelElement);
  }

  handleProperties() {
    if (this.inputContainerElement.length === 0) {
      console.error('Radio Button must be placed inside a [mz-radio-button-container] tag', this.inputElement);
      return;
    }

    super.executePropHandlers();
  }

  handleLabel() {
    const spanElement = document.createElement('span');
    spanElement.innerText = this.label;
    this.renderer.invokeElementMethod(this.inputElement, 'after', [spanElement]);
  }

  handleWithGap() {
    this.renderer.setElementClass(this.inputElement[0], 'with-gap', this.withGap);
  }
}

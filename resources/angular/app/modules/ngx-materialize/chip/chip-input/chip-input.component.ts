import { Component, ElementRef, EventEmitter, forwardRef, Input, NgZone, OnDestroy, OnInit, Output } from '@angular/core';
import { ControlValueAccessor, NG_VALUE_ACCESSOR } from '@angular/forms';

declare var $: any;

@Component({
  selector: 'mz-chip-input',
  templateUrl: './chip-input.component.html',
  styleUrls: ['./chip-input.component.scss'],
  providers: [
    {
      provide: NG_VALUE_ACCESSOR,
      useExisting: forwardRef(() => MzChipInputComponent),
      multi: true,
    },
  ],
})
export class MzChipInputComponent implements ControlValueAccessor, OnInit, OnDestroy {
  @Input() autocompleteOptions: any;
  @Input() placeholder: string;
  @Input() secondaryPlaceholder: string;
  @Output() add = new EventEmitter();
  @Output() delete = new EventEmitter();
  @Output() select = new EventEmitter();

  get value(): any[] {
    return this.chipInputElement.data('data') as any[];
  }

  private chipInputElement: any;

  constructor(
    private elementRef: ElementRef,
    private zone: NgZone,
  ) { }

  ngOnInit() {
    this.initElements();
    this.initMaterializeChip();
  }

  ngOnDestroy() {
    this.chipInputElement.off('chip.add');
    this.chipInputElement.off('chip.delete');
    this.chipInputElement.off('chip.select');
  }

  initElements() {
    this.chipInputElement = $(this.elementRef.nativeElement);
  }

  initMaterializeChip(value?: any[]) {
    // fix issue autocomplete is not a function
    // https://github.com/Dogfalo/materialize/issues/4401
    this.zone.runOutsideAngular(() => {
      setTimeout(() => {
        this.chipInputElement.data({
          autocompleteOptions: this.autocompleteOptions,
          data: value || this.value,
          placeholder: this.placeholder,
          secondaryPlaceholder: this.secondaryPlaceholder,
        });
      });
    });

    this.chipInputElement.on('chip.add', (event, chip: any) => {
      this.onChangeCallback(this.value);
      this.add.emit(chip);
    });
    this.chipInputElement.on('chip.delete', (event, chip: any) => {
      this.onChangeCallback(this.value);
      this.delete.emit(chip);
    });
    this.chipInputElement.on('chip.select', (event, chip: any) => {
      this.select.emit(chip);
    });
  }

  //#region ControlValueAccessor

  registerOnChange(fn: any) {
    this.onChangeCallback = fn;
  }

  registerOnTouched(fn: any) { }

  setDisabledState(isDisabled: boolean) { }

  writeValue(value: any[]) {
    if (value && value !== this.value) {
      this.initMaterializeChip(value);
    }
  }

  //#endregion ControlValueAccessor

  private onChangeCallback = (data: any[]) => {};
}

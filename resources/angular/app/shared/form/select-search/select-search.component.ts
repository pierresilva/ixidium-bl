import {
  AfterViewInit,
  Component, EventEmitter,
  forwardRef,
  Input,
  OnInit, Output,
} from '@angular/core';
import {ControlValueAccessor, FormControl, FormGroup, NG_VALIDATORS, NG_VALUE_ACCESSOR, Validator} from '@angular/forms';
import {Select2OptionData} from 'ng2-select2';
import {Observable} from 'rxjs/Observable';

declare var $: any;

@Component({
  selector: 'renova-select-search',
  templateUrl: './select-search.component.html',
  styleUrls: ['./select-search.component.scss'],
  providers: [
    {
      provide: NG_VALUE_ACCESSOR,
      useExisting: forwardRef(() => SelectSearchComponent),
      multi: true,
    },
    {
      provide: NG_VALIDATORS,
      useExisting: forwardRef(() => SelectSearchComponent),
      multi: true,
    }]
})
export class SelectSearchComponent implements OnInit, AfterViewInit, ControlValueAccessor, Validator {

  public _selectData: Observable<Array<Select2OptionData>>;
  public _options: Select2Options;
  public _multiple = false;
  public _value: Observable<any | any[]>;
  public _label: string;
  public _group: FormGroup = null;
  public _field: FormControl = null;
  public _maxLength: number = null;
  public _minLength: number = null;
  public _required = false;
  public _disabledFrom = false;
  public _idsFrom = null;
  public _textsFrom = null;
  public _dropDownParent = null;


  @Input() set value(data: string | null) {
    if (data) {
      this.writeValue(data);
    }
  }

  @Input() set disabledFrom(data: string | null) {
    if (data) {
      this._disabledFrom = true;
    } else {
      this._disabledFrom = false;
    }
  }

  @Input() set selectData(data: any[]) {

    this._selectData = Observable.create(obs => {
      if (this._idsFrom && this._textsFrom  ) {
        const dataSelect: any[] = [];
        for (let i = 0; i < data.length; i++) {
          dataSelect.push({
            id: data[i][this._idsFrom],
            text: data[i][this._textsFrom]
          });
        }
        data = dataSelect;
      }

      obs.next(data);
      obs.complete();
    });

  }

  @Input() set label(data: string) {
    if (data) {
      this._label = data;
    }
  }

  @Input() set multiple(data: boolean) {
    this._multiple = data ? data : false;
  }

  @Input() set formGroup(data: FormGroup) {
    this._group = data;
  }

  @Input() set formControlName(data) {
    this._field = data;
  }

  @Input() set maxLength(data) {
    this._maxLength = data;
  }

  @Input() set minLength(data) {
    this._minLength = data;
  }

  @Input() set required(data) {
    this._required = data;
  }

  @Input() set idsFrom(data) {
    if (data) {
      this._idsFrom = data;
    }
  }

  @Input() set  textsFrom(data) {
    if (data) {
      this._textsFrom = data;
    }
  }

  @Input() set dropDownParent(data: any) {
    this._dropDownParent = data;
  }

  @Output() eventChangedOut = new EventEmitter();

  eventChanged(event) {
    this.eventChangedOut.emit(event);
  }

  constructor() {
  }

  public writeValue(obj) {

    this._value = Observable.create(obs => {
      obs.next(obj);
      obs.complete();
    });

  }

  public registerOnChange(fn: any) {

    this.propagateChange = fn;

  }

  public validate(c: FormControl) {
    let errors: any = {};
    for (let error in c.errors) {
      if (c.errors[error]) {
        errors[error] = {
          valid: false,
        };
      }
    }

    return {};
  }

  public registerOnTouched() {
  }

  private onChange(event) {
    this.propagateChange(event.value);
  }

  private propagateChange = (_: any) => {
  }

  ngOnInit() {
    const options = {};
    if (this._multiple) {
      options['multiple'] = true;
    }
    if (this._dropDownParent) {
      options['dropdownParent'] = $(`#${this._dropDownParent}`);
    }
    this._options = options;
  }

  ngAfterViewInit() {
    // $.fn.modal.Constructor.prototype.enforceFocus = function() {};
  }

  _changed(event) {
    this.propagateChange(event.value);
  }

}

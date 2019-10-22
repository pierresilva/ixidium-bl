import {
  ChangeDetectorRef,
  Directive,
  ElementRef,
  HostBinding,
  Input,
  OnDestroy,
  OnInit,
  Optional,
  Renderer,
  Output,
  EventEmitter,
  HostListener
} from '@angular/core';
import {BehaviorSubject} from 'rxjs';
import {NgModel} from '@angular/forms';

declare var M: any;

@Directive({
  selector: 'input[mzDatepicker], input[mz-datepicker]',
  providers: [NgModel]
})
export class MzDatepickerDirective implements OnInit, OnDestroy {
  _minDate = new BehaviorSubject<any>(null);
  _maxDate = new BehaviorSubject<any>(null);
  _defDate = new BehaviorSubject<any>(null);
  _ngModel = new BehaviorSubject<any>(null);

  @Input() set ngModel(value: any) {
    this._ngModel.next(value);
  }
  @HostBinding('class.datepicker') true;
  @Input() id: string;
  @Input() placeholder: string;
  @Input() label: string;
  @Input() options: any = {};
  @Input() format: string;


  i18n = {
    cancel: 'Cancelar',
    clear: 'Limpiar',
    done: 'Ok',
    months:
      [
        'Enero',
        'Febrero',
        'Marzo',
        'Abril',
        'Mayo',
        'Junio',
        'Julio',
        'Agosto',
        'Septiembre',
        'Octubre',
        'Noviembre',
        'Diciembre'
      ],
    monthsShort:
      [
        'Ene',
        'Feb',
        'Mar',
        'Abr',
        'May',
        'Jun',
        'Jul',
        'Ago',
        'Sep',
        'Oct',
        'Nov',
        'Dic'
      ],
    weekdays:
      [
        'Domingo',
        'Lunes',
        'Martes',
        'Miercoles',
        'Jueves',
        'Viernes',
        'Sabado'
      ],
    weekdaysShort:
      [
        'Dom',
        'Lun',
        'Mar',
        'Mie',
        'Jue',
        'Vie',
        'Sab'
      ],
    weekdaysAbbrev: ['D', 'L', 'M', 'M', 'J', 'V', 'S']
  };

  yearRange = 100;

  @Input() set minDate(data) {
    this._minDate.next(data);
  }

  @Input() set maxDate(data) {
    this._maxDate.next(data);
  }

  @Input() set defDate(data) {
    this._defDate.next(data ? new Date(data).getTime() + 86400000 : null);
  }

  @Output() datepicker = new EventEmitter();

  instances: any;

  constructor(
    private changeDetectorRef: ChangeDetectorRef,
    private elementRef: ElementRef,
    private renderer: Renderer,
  ) {

  }

  ngOnInit() {

    this.createLabelElement();
    this.options.i18n = this.i18n;
    this.options.yearRange = this.yearRange;
    this._minDate.subscribe((data) => {
      if (data) {
        let minDate: any;
        let value: any;
        minDate = new Date(data).getTime() + 86400000;
        value = this._ngModel.value ? new Date(this._ngModel.value).getTime() + 86400000 : null;
        this.options.minDate = new Date(minDate);
        if (this._defDate) {
          this.options.defaultDate = this._defDate;
        }
        // this.options.defaultDate = new Date(value);
        // this.options.setDefaultDate = true;
        this.initDatepicker();
      }
    });

    this._maxDate.subscribe((data) => {
      if (data) {
        let maxDate: any;
        maxDate = new Date(data).getTime() + 86400000;
        this.options.maxDate = new Date(maxDate);
        // this.options.defaultDate = new Date(maxDate);
        // this.options.setDefaultDate = true;
        this.initDatepicker();
      }
    });

    this.initDatepicker();
  }

  initDatepicker() {
    this.options.format = 'yyyy-mm-dd';
    if (this.format) {
      this.options.format = this.format;
    }
    setTimeout(() => {
      this.instances = M.Datepicker.init(this.elementRef.nativeElement, this.options);
      this.datepicker.emit(this.instances[0]);
      M.updateTextFields();
    }, 0);
  }

  ngOnDestroy() {
    if (this.instances) {
      this.instances.destroy();
    }
  }

  createLabelElement() {
    let element: any;
    element = document.getElementById(this.id);
    let label: any;
    label = document.createElement('label');
    label.setAttribute('for', this.id);
    label.innerText = this.label;
    element.parentNode.insertBefore(label, element.nextSibling);
  }


  @HostListener('change') dateChanges() {
    this.elementRef.nativeElement.dispatchEvent(new CustomEvent('input'));
  }

  addYears(dt, n) {
    return new Date(dt.setFullYear(dt.getFullYear() + n));
  }

}

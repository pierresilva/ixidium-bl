import {
  Component,
  Optional,
  Inject,
  Input,
  ViewChild, OnInit, AfterViewInit, ElementRef, AfterContentInit,
} from '@angular/core';

import {
  NgModel,
  NG_VALUE_ACCESSOR,
  NG_VALIDATORS,
  NG_ASYNC_VALIDATORS,
} from '@angular/forms';
import {BehaviorSubject} from 'rxjs/BehaviorSubject';
import {ElementBase} from '../element-base';
import {animations} from '../animations';

declare var M: any;

@Component({
  selector: 'renova-form-select',
  templateUrl: './select.component.html',
  styleUrls: ['./select.component.scss'],
  animations,
  providers: [{
    provide: NG_VALUE_ACCESSOR,
    useExisting: FormSelectComponent,
    multi: true,
  }],
})
export class FormSelectComponent extends ElementBase<string> implements OnInit, AfterViewInit, AfterContentInit {
  @Input() public label: string;
  @Input() public placeholder: string;
  @Input() public required: boolean;
  @Input() public class: string;

  private _selectOptions = new BehaviorSubject<any[]>([]);

  @Input() set options(data) {
    this._selectOptions.next(data);
  }

  private _selectedOption = new BehaviorSubject<any>(null);
  /*@Input() set selected(data) {
      this._selectedOption.next(data);
  }*/

  selectOptions = [];
  filteredOptions = [];

  validationMessages: any;

  @ViewChild(NgModel) model: NgModel;

  element: ElementRef;

  thisIdentifier = ++identifier;

  constructor(
    @Optional() @Inject(NG_VALIDATORS) validators: Array<any>,
    @Optional() @Inject(NG_ASYNC_VALIDATORS) asyncValidators: Array<any>,
    private el: ElementRef
  ) {
    super(validators, asyncValidators);
  }

  ngOnInit() {
    this.element = this.el.nativeElement;
    let that = this;

    this.validationMessages = {
      error: 'Este campo es requerido!',
      success: 'Campo valido!'
    };

    this._selectedOption.subscribe(
      data => {
        console.log(data);
        let current = $(that.element).find('[id="' + data + '"]').find('span').text();
        if (current) {
          $(that.element).find('#basic-select-' + that.thisIdentifier + ' option[value="' + data + '"]').prop('selected', true);
          $(that.element).find('#input-select-' + that.thisIdentifier).val(current);
        } else {
          $(that.element).find('#input-select-' + that.thisIdentifier).val(that.placeholder);
        }
      }
    );

    this._selectOptions.subscribe(
      data => {
        this.selectOptions = [];
        if (data.length > 0) {
          for (let i = 0; i < data.length; i++) {
            let option = {
              text: '',
              id: '',
            };

            option.id = data[i].id;
            if (!!data[i].title) {
              option.text = data[i].title;
            } else if (!!data[i].name) {
              option.text = data[i].name;
            } else if (!!data[i].text) {
              option.text = data[i].text;
            } else {
              option.text = 'Not set!';
            }

            this.selectOptions.push(option);
            this.filteredOptions = this.selectOptions;
          }
        }
      }
    );
  }

  ngAfterViewInit() {
    M.updateTextFields();

    this._selectedOption.next(this.value);

    let that = this;

    $(this.element).find('li:first-child').css({
      'margin-top': '50px;',
      color: 'red',
    });

    $(document).on('click', function (e) {
      e.stopPropagation();
      if (!$(e.target).is('#search-select-' + that.thisIdentifier)) {
        $('#select-options-' + that.thisIdentifier).addClass('select-options-hidden'); // make all inactive
      }

    });

    $('#select-options-' + this.thisIdentifier).css({
      height: '200px',
    });

    $('#input-select-' + this.thisIdentifier).on('click focus', function (e) {
      e.stopPropagation();
      $('#select-options-' + that.thisIdentifier).toggleClass('select-options-hidden');
      if ($('#select-options-' + that.thisIdentifier).hasClass('select-options-hidden')) {
        $('#select-caret-' + that.thisIdentifier).text('▼');
      } else {
        $('#select-caret-' + that.thisIdentifier).text('▲');
      }
    });

    $(this.element).on('click', 'li', function (e) {
      e.stopPropagation();
      let id = $(this).attr('id');
      $(that.element).find('#basic-select-' + that.thisIdentifier + ' option[value="' + id + '"]').prop('selected', true);
      $(that.element).find('#input-select-' + that.thisIdentifier).val($(this).find('span').text());
      $(that.element).find('#select-options-' + that.thisIdentifier).toggleClass('select-options-hidden');
      that.value = id;
      if ($(that.element).find('#select-options-' + that.thisIdentifier).hasClass('select-options-hidden')) {
        $(that.element).find('#select-caret-' + that.thisIdentifier).text('▼');
      } else {
        $(that.element).find('#select-caret-' + that.thisIdentifier).text('▲');
      }
      $('#search-select-' + that.thisIdentifier).val('');
      that.filteredOptions = that.selectOptions;
    });

    $('#select-options-' + this.thisIdentifier).scroll(function () {
      if ($(this).scrollTop() > 0) {
        $('#search-select-' + that.thisIdentifier).css({top: $(this).scrollTop() + 'px'});
      }
    });

    $('#search-select-' + this.thisIdentifier).on('keyup', function () {
      $('#search-select-' + that.thisIdentifier).css({top: '0px'});
      $('#select-options-' + that.thisIdentifier).scrollTop(0);
    });

  }

  ngAfterContentInit() {
    // this.options = $(this.element).find('option');
    // console.log(this.options);
    // for (let i = 0; i < this.options.length; i++) {
    //     console.log(this.options[i]['attributes']['value']['value']);
    // }
  }

  filterOptions(event) {
    let term = event.target.value;
    this.filteredOptions = [];
    if (term !== '') {

      for (let key of this.selectOptions) {
        if (key.hasOwnProperty('text') &&
          key.text.toLowerCase().indexOf(term) !== -1 &&
          key.text.toLowerCase() !== term) {
          this.filteredOptions.push(key);
        }
      }
    } else {
      this.filteredOptions = this.selectOptions;
    }
  }

  getCurrentText() {
    setTimeout(() => {
      for (let i = 0; i < this.selectOptions.length; i++) {
        if (this.selectOptions[i].id === this.value) {
          return this.selectOptions[i].text;
        }
      }

      return 'Seleccione...';
    }, 100);

  }
}

let identifier = 0;

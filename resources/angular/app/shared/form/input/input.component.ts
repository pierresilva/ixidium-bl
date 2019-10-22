import {
  Component,
  Optional,
  Inject,
  Input,
  ViewChild, OnInit, AfterViewInit,
} from '@angular/core';

import {
  NgModel,
  NG_VALUE_ACCESSOR,
  NG_VALIDATORS,
  NG_ASYNC_VALIDATORS,
} from '@angular/forms';

import {ElementBase} from '../element-base';
import {animations} from '../animations';

declare var M: any;

@Component({
  selector: 'renova-form-text',
  template: `
    <div class="input-field col" [ngClass]="class ? class : 's12'">
      <input type="text"
             [required]="required"
             class="validate"
             [ngClass]="(required && !value) ? 'invalid' : ''"
             [name]="identifier"
             [placeholder]="placeholder"
             [(ngModel)]="value"
             (change)="onChange($event)"
             (keyup)="onChange($event)"
             [id]="identifier"/>
      <ng-container *ngIf="label">
        <label [attr.for]="identifier" [attr.data-error]="validationMessages.error"
               [attr.data-success]="validationMessages.success">{{label}} <span *ngIf="required" class="red-text">*</span>
        </label>
      </ng-container>

    </div>
  `,
  animations,
  providers: [{
    provide: NG_VALUE_ACCESSOR,
    useExisting: FormTextComponent,
    multi: true,
  }],
})
export class FormTextComponent extends ElementBase<string> implements OnInit, AfterViewInit {
  @Input() public label: string;
  @Input() public placeholder: string;
  @Input() public required: boolean;
  @Input() public class: string;

  @ViewChild(NgModel) model: NgModel;

  public identifier = `form-text-${identifier++}`;

  public validationMessages: any;

  constructor(
    @Optional() @Inject(NG_VALIDATORS) validators: Array<any>,
    @Optional() @Inject(NG_ASYNC_VALIDATORS) asyncValidators: Array<any>,
  ) {
    super(validators, asyncValidators);
  }

  onChange(event) {
    console.log(this.model.value, this.value);
  }

  ngOnInit() {
    this.validationMessages = {
      error: 'Este campo es requerido!',
      success: 'Campo valido!'
    };
    console.log(this.model.value, this.value);
  }

  ngAfterViewInit() {
    // M.updateTextFields();
  }
}

let identifier = 0;

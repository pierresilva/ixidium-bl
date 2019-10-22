import {Directive, forwardRef, Attribute} from '@angular/core';
import {Validator, AbstractControl, NG_VALIDATORS} from '@angular/forms';

@Directive({
  selector: '[validateJson][formControlName],[validateJson][formControl],[validateJson][ngModel]',
  providers: [
    {
      provide: NG_VALIDATORS,
      useExisting: forwardRef(() => JsonValidationDirective),
      multi: true
    }
  ]
})
export class JsonValidationDirective implements Validator {
  constructor(
    @Attribute('validateJson') public validateJson: string
  ) {
  }

  validate(c: AbstractControl): { [key: string]: any } {
    // self value (e.g. retype password)
    let v = c.value;

    // control value (e.g. password)
    let e = c.root.get(this.validateJson);

    if (v) {
      try {
        JSON.parse(v);
      } catch (e) {
        return {
          validateJson: false
        };
      }
    }

    return null;
  }
}

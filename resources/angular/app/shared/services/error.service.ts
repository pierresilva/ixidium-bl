import {Injectable} from '@angular/core';
import {MzToastService} from '@ngx-materialize';

@Injectable()
export class ErrorService {
    errors: any;

    constructor(private toastService: MzToastService) {
    }

    setErrors(error: any) {
        if (error.error.message) {
            this.errors = [];
            let _error;
            _error = error.error.message;

            this.errors.push(_error);

            this.toastService.show(
                _error,
                4000,
                'red'
            );
        }

        if (error.error.errors) {
            let _errors: any;
            let key: any;
            _errors = [];
            this.errors = [];
            for (key in error.error.errors) {
              if (true) {
                _errors.push(error.error.errors[key]);
                this.toastService.show(
                    error.error.errors[key],
                    4000,
                    'red'
                );
              }
            }

            this.errors = _errors;
        }
    }

    getErrors() {
        return this.errors;
    }
}

import {Injectable} from '@angular/core';
import {ApiService} from './api.service';
import {BehaviorSubject} from 'rxjs/BehaviorSubject';


@Injectable()
export class ValidateSlugService {
  slugValidate = new BehaviorSubject<boolean>(false);
  constructor(
    private api: ApiService
  ) {}

  validateCode(table, value, column) {
    this.api.get('validate-slug?table=' + table + '&value=' + value + '&column=' + column)
      .subscribe(
        resp => {
          return this.slugValidate.next(resp.data);
        });
  }
}

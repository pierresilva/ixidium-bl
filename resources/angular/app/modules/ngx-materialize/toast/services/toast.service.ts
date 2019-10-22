import { Injectable } from '@angular/core';

declare var M: any;

@Injectable()
export class MzToastService {

  show(message: string, displayLength: number = 5000, className: string = '', completeCallback: Function = () => {}) {
    M.toast({html: message, displayLength: displayLength, classes: className, completeCallback: completeCallback});
  }
}

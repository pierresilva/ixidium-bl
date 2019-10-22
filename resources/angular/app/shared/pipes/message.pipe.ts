import {Pipe, PipeTransform} from '@angular/core';
import {MessageService} from '../services/message.service';

@Pipe({
    name: 'message'
})
export class MessagePipe implements PipeTransform {
    constructor(private messageService: MessageService) {
    }

    transform(value: any, args?: any): any {
        let message;
        message = value;
        return this.messageService.getMessages()[value] ? this.messageService.getMessages()[value] : message;
    }
}

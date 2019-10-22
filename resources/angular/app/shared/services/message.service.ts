import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {Messages} from '../models/messages';

@Injectable()

export class MessageService {
    private messages: Messages;

    constructor(private http: HttpClient) {
    }

    load(url) {
        return new Promise((resolve) => {
            this.http.get<Messages>(url).map(res => res)
                .subscribe(messages => {
                    this.messages = messages;
                    resolve();
                });
        });
    }

    getMessages(): Messages {

        return this.messages;
    }

    get(key) {
        return this.getMessages()[key] ? this.getMessages()[key] : key;
    }

}

import {Component, Input} from '@angular/core';

@Component({
    selector: 'validation',
    template: `
        <div *ngFor="let message of messages" class="validation">{{message}}</div>
    `,
    styles: [`
        .validation {
            margin: 0px;
            margin-top: -18px;
            font-size: 12px;
            color: red;
        }`
    ]
})

export class ValidationComponent {
    @Input() messages: Array<string>;
}

import {Directive, Input, OnInit, TemplateRef, ViewContainerRef} from '@angular/core';
import {AuthService} from '../services/auth.service';

@Directive({
    selector: '[userCan]'
})
export class UserCanDirective implements OnInit {

    actions = null;

    constructor(private auth: AuthService,
                private templateRef: TemplateRef<any>,
                private viewContainer: ViewContainerRef) {
    }

    @Input() set userCan(actions) {
        this.actions = actions;
    }

    ngOnInit() {
        this.auth.userActions.subscribe(data => {
            let actions = data;

            if (typeof data === 'string') {
                actions = JSON.parse(data);
            }

            for (let i = 0; i < this.actions.length; i++) {
                for (let j = 0; j < actions.length; j++) {
                    if (actions[j] === this.actions[i]) {
                        this.viewContainer.createEmbeddedView(this.templateRef);
                        return true;
                    }
                }
            }

            this.viewContainer.clear();

            return false;
        });
    }
}

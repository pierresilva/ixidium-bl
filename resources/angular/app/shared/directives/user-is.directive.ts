import {Directive, Input, OnInit, TemplateRef, ViewContainerRef} from '@angular/core';
import {AuthService} from '../services/auth.service';

@Directive({
    selector: '[userIs]',
})
export class UserIsDirective implements OnInit {

    profiles = null;

    constructor(private auth: AuthService,
                private templateRef: TemplateRef<any>,
                private viewContainer: ViewContainerRef) {
    }

    @Input() set userIs(roles) {
        this.profiles = roles;
    }

    ngOnInit() {

        if (this.profiles.length) {
            this.auth.userProfiles.subscribe(data => {
                let profiles = data;

                if (typeof data === 'string') {
                    profiles = JSON.parse(data);
                }

                if (profiles) {
                    for (let i = 0; i < this.profiles.length; i++) {

                        for (let j = 0; j < profiles.length; j++) {
                            if (profiles[j] === this.profiles[i]) {
                                this.viewContainer.createEmbeddedView(this.templateRef);
                                return true;
                            }
                        }
                    }
                }

                this.viewContainer.clear();

                return false;
            });
        }
        return true;
    }

}

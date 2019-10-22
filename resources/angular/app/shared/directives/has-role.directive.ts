import {Directive, Input, OnInit, TemplateRef, ViewContainerRef} from '@angular/core';
import {AuthService} from '../services/auth.service';

@Directive({
    selector: '[renovaHasRole]'
})
export class HasRoleDirective implements OnInit {

    roles = null;

    constructor(private auth: AuthService,
                private templateRef: TemplateRef<any>,
                private viewContainer: ViewContainerRef) {
    }

    @Input() set hasRole(roles) {
        this.roles = roles;
    }

    ngOnInit() {
        this.auth.userRoles.subscribe(data => {
            for (let i = 0; i < this.roles.length; i++) {
                for (let j = 0; j < data.length; j++) {
                    if (data[j] === this.roles[i]) {
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

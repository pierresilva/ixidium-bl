import {Directive, Input, OnInit, TemplateRef, ViewContainerRef} from '@angular/core';
import {AuthService} from '../services/auth.service';

@Directive({
    selector: '[renovaHasPermission]'
})
export class HasPermissionDirective implements OnInit {

    permissions = null;

    constructor(private auth: AuthService,
                private templateRef: TemplateRef<any>,
                private viewContainer: ViewContainerRef) {
    }

    @Input() set hasPermission(permissions) {
        this.permissions = permissions;
    }

    ngOnInit() {
        this.auth.userPermissions.subscribe(data => {
            for (let i = 0; i < this.permissions.length; i++) {
                for (let j = 0; j < data.length; j++) {
                    if (data[j] === this.permissions[i]) {
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

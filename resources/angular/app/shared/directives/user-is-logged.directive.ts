import {Directive, Input, OnInit, TemplateRef, ViewContainerRef} from '@angular/core';
import {AuthService} from '../services/auth.service';
import {JwtService} from '../services/jwt.service';

@Directive({
  selector: '[userIsLogged]'
})
export class UserIsLoggedDirective implements OnInit {

    condition: any;

    constructor(private auth: AuthService,
                private jwt: JwtService,
                private templateRef: TemplateRef<any>,
                private viewContainer: ViewContainerRef) {
    }

    @Input() set isLogged(condition) {
        this.condition = condition;
    }

    ngOnInit() {

        this.auth.isAuthenticated.subscribe((data) => {
            // ToDo: Revisar porque no entra aqui.
            if (data === this.condition) {
                this.viewContainer.createEmbeddedView(this.templateRef);
                return true;
            } else {
                this.viewContainer.clear();
                return false;
            }
        });
    }

}

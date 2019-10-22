import {Directive, OnDestroy, OnInit, TemplateRef, ViewContainerRef} from '@angular/core';
import {LoadingHttpService} from '../services/loading-http.service';

@Directive({
    selector: '[appLoading]'
})
export class LoadingDirective implements OnInit, OnDestroy {

    constructor(
        private loading: LoadingHttpService,
        private templateRef: TemplateRef<any>,
        private viewContainer: ViewContainerRef
    ) { }

    ngOnInit() {
        this.loading.isLoading
            .subscribe((data) => {
                if (data === true) {
                    this.viewContainer.createEmbeddedView(this.templateRef);
                    return true;
                } else {
                    this.viewContainer.clear();
                    return false;
                }
            });
    }

    ngOnDestroy() {
    }

}

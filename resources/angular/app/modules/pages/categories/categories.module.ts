import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {SharedModule} from '../../../shared/shared.module';

import {CategoriesRoutingModule} from './categories-routing.module';
import {CategoriesListComponent} from './categories-list/categories-list.component';
import {CategoriesFormComponent} from './categories-form/categories-form.component';
import {CategoriesComponent} from './categories.component';


@NgModule({
    imports: [
        CommonModule,
        CategoriesRoutingModule,
        SharedModule,
    ],
    declarations: [
        CategoriesListComponent,
        CategoriesFormComponent,
        CategoriesComponent,
    ],
    entryComponents: []
})

export class CategoriesModule {
}

import {NgModule} from '@angular/core';
import {Routes, RouterModule} from '@angular/router';
import { PagesComponent } from './pages.component';
// generated components imports here //

const routes: Routes = [
    {
        path: '',
        component: PagesComponent,
        data: {
            'title': 'Pages',
        }

    },
    // generated components routes here //
];

@NgModule({
    imports: [
        RouterModule.forChild(routes)
    ]
})
export class PagesRoutingModule {
}

import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { PouchdbComponent } from './pouchdb.component';
// generated components imports here //

const routes: Routes = [
  {
    path: '',
    component: PouchdbComponent,
    data: {
      'title': 'Pouchdb',
    },
  },
  // generated components routes here //
];

@NgModule({
  imports: [
    RouterModule.forChild(routes)
  ]
})
export class PouchdbRoutingModule {
}

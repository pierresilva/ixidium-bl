import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { CouchdbComponent } from './couchdb.component';
import { FileUploadComponent } from './file-upload/file-upload.component';
// generated components imports here //

const routes: Routes = [
  {
    path: '',
    component: CouchdbComponent,
    data: {
      'title': 'Couchdb',
    },
  },
  {
    path: 'upload',
    component: FileUploadComponent,
    data: {
      title: 'File Upload',
    }
  }
  // generated components routes here //
];

@NgModule({
  imports: [
    RouterModule.forChild(routes)
  ]
})
export class CouchdbRoutingModule {
}

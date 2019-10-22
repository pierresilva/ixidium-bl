import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { CouchdbRoutingModule } from './couchdb-routing.module';
import { CouchdbComponent } from './couchdb.component';
import { SharedModule } from '../../shared/shared.module';
import { FileUploadComponent } from './file-upload/file-upload.component';

// generated components imports here //

// generated services imports here //

@NgModule({
  imports: [
    CommonModule,
    CouchdbRoutingModule,
    SharedModule,
  ],
  exports: [
    CouchdbComponent,
    // generated components declarations here //
  ],
  declarations: [
      CouchdbComponent,
      FileUploadComponent,
      // generated components declarations here //
  ],
  providers: [
    // generated services providers here //
  ],
  entryComponents: [
    CouchdbComponent,
    // generated components declarations classes here //
  ],
})
export class CouchdbModule { }

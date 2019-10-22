import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { PouchdbComponent } from './pouchdb.component';
import { PouchdbService } from './pouchdb.service';
import { PouchdbRoutingModule } from './pouchdb-routing.module';
import { SharedModule } from '../../shared/shared.module';

@NgModule({
  declarations: [PouchdbComponent],
  imports: [
    CommonModule,
    SharedModule,
    PouchdbRoutingModule,
  ],
  providers: [
    PouchdbService,
  ],
})
export class PouchdbModule { }

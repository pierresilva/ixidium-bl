import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { FilemanagerRoutingModule } from './filemanager-routing.module';
import { FilemanagerComponent } from './filemanager.component';
import { SharedModule } from '../../shared/shared.module';
import { FilemanagerImageUploadComponent } from './filemanager-image-upload/filemanager-image-upload.component';

@NgModule({
  imports: [
    CommonModule,
    SharedModule,
    FilemanagerRoutingModule
  ],
  declarations: [
    FilemanagerComponent,
    FilemanagerImageUploadComponent
  ],
  exports: [
    FilemanagerComponent
  ],
  entryComponents: [
    FilemanagerComponent
  ]
})
export class FilemanagerModule { }

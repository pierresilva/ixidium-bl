import {NgModule} from '@angular/core';
import {Routes, RouterModule} from '@angular/router';
import {FilemanagerComponent} from './filemanager.component';
import {FilemanagerImageUploadComponent} from './filemanager-image-upload/filemanager-image-upload.component';

const routes: Routes = [
    {
        path: 'image-upload',
        component: FilemanagerImageUploadComponent,
    },
    {
        path: 'manage/:fieldId',
        component: FilemanagerComponent,
        data: {
            title: 'File Manager',
        }
    },
];

@NgModule({
    imports: [RouterModule.forChild(routes)],
    exports: [RouterModule]
})
export class FilemanagerRoutingModule {
}

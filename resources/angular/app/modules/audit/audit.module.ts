import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {AuditRoutingModule} from './audit-routing.module';
import {AuditComponent} from './audit.component';
import {SharedModule} from '../../shared/shared.module';
import {ActivitiesComponent} from './activities/activities.component';
import {ModelsComponent} from './models/models.component';

@NgModule({
    imports: [
        CommonModule,
        AuditRoutingModule,
        SharedModule,
    ],
    declarations: [
        AuditComponent,
        ActivitiesComponent,
        ModelsComponent
    ]
})
export class AuditModule {
}

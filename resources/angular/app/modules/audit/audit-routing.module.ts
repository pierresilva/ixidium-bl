import {NgModule} from '@angular/core';
import {Routes, RouterModule} from '@angular/router';
import {AuditComponent} from './audit.component';
import {ActivitiesComponent} from './activities/activities.component';
import {ModelsComponent} from './models/models.component';

const routes: Routes = [
  {
    path: '',
    component: AuditComponent,
    data: {
      title: 'Auditoria'
    }
  },
  {
    path: 'activities/models',
    component: ModelsComponent,
    data: {
      title: 'Auditoria - Modelos'
    }
  },
  {
    path: 'activities/list/:model',
    component: ActivitiesComponent,
    data: {
      title: 'Auditoria - Actividad'
    }
  },
  {
    path: 'activities',
    component: ActivitiesComponent,
    data: {
      title: 'Auditoria - Actividades'
    }
  },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class AuditRoutingModule {
}

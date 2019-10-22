import {NgModule} from '@angular/core';
import {Routes, RouterModule} from '@angular/router';
import {ReporterComponent} from './reporter.component';
import {ReportsFormComponent} from './reports-form/reports-form.component';
import {ReportComponent} from './report/report.component';
import {DataTablesSampleComponent} from './components/data-tables-sample/data-tables-sample.component';
// generated components imports here //

const routes: Routes = [
  {
    path: '',
    component: ReporterComponent,
    data: {
      'title': 'Reporter',
    },
  },
  {
    path: 'sample',
    component: DataTablesSampleComponent,
    data: {
      'title': 'Reporter',
    },
  },
  {
    path: 'report/view/:reportId/:reportConnection/:reportView/:reportFields',
    component: ReportComponent,
    data: {
      title: 'Reporte',
    }
  },

  {
    path: 'report/create',
    component: ReportsFormComponent,
    data: {
      title: 'Nuevo reporte'
    }
  },
  {
    path: 'report/edit/:reportId',
    component: ReportsFormComponent,
    data: {
      title: 'Editar reporte',
    }
  }
  // generated components routes here //
];

@NgModule({
  imports: [
    RouterModule.forChild(routes)
  ]
})
export class ReporterRoutingModule {
}

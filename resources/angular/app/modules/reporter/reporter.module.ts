import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {ReporterRoutingModule} from './reporter-routing.module';
import {ReporterComponent} from './reporter.component';
import {SharedModule} from '../../shared/shared.module';
import {ReportsFormComponent} from './reports-form/reports-form.component';
import {ReportComponent} from './report/report.component';
import {ReportFilterComponent} from './report-filter/report-filter.component';
import {DataTablesSampleComponent} from './components/data-tables-sample/data-tables-sample.component';

// generated components imports here //

// generated services imports here //

@NgModule({
    imports: [
        CommonModule,
        ReporterRoutingModule,
        SharedModule,
    ],
    exports: [
        ReporterComponent,
        ReportsFormComponent,
        ReportComponent,
        ReportFilterComponent,
        // generated components declarations here //
    ],
    declarations: [
        ReporterComponent,
        ReportsFormComponent,
        ReportComponent,
        ReportFilterComponent,
        DataTablesSampleComponent,
        // generated components declarations here //
    ],
    providers: [
        // generated services providers here //
    ],
    entryComponents: [
        ReporterComponent,
        ReportsFormComponent,
        ReportComponent,
        ReportFilterComponent,
        // generated components declarations classes here //
    ],
})
export class ReporterModule {
}

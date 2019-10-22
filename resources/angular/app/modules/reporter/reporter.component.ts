import {Component, OnInit, ViewChild} from '@angular/core';
import {ApiService} from '../../shared/services/api.service';

declare var $: any;
declare var M: any;
import * as moment from 'moment';
import {Report} from './models/report';
import {PaginationMeta} from '../../shared/layout/pagination/models/pagination-meta';
import {MzToastService} from '@ngx-materialize';
import {AlertService} from '../../shared/services/alert.service';
import {Router} from '@angular/router';

@Component({
    selector: 'renova-reporter',
    templateUrl: './reporter.component.html',
    styleUrls: ['./reporter.component.scss']
})

export class ReporterComponent implements OnInit {

    reports: Report[];
    report: Report;
    meta: PaginationMeta;

    message: any;

    constructor(private api: ApiService,
                private toast: MzToastService,
                private alert: AlertService,
                private router: Router) {
    }

    ngOnInit() {
        this.report = new Report();
        this.reports = [];
        this.meta = new PaginationMeta();
        this.getReports();
    }

    getReports(page = 1, search = $('#search-reports').val()) {
        this.api.get('reporter/reports?page=' + page + '&search=' + search)
            .subscribe(res => {
                this.reports = res.data;
                this.meta = res.meta;
            });
    }

    newReport() {
        this.router.navigate(['/reporter/report/create']);
    }

    deleteReport(report) {

        this.alert.confirmThis(
            'confirm',
            'Desea eliminar el reporte?',
            'Esto eliminara el reporte ' + report.name + ' permanentemente!',
            () => {
                this.api.delete('reporter/reports/' + report.id, report)
                    .subscribe(res => {
                        this.toast.show(
                            '' + res.message,
                            500,
                            'green'
                        );
                        this.getReports();
                    });
            },
            () => {},
            'white-text orange'
            );
    }

    editReport(report) {
        this.router.navigate(['/reporter/report/edit', report.id]);
    }

    viewReport(report) {
        this.router.navigate(['/reporter/report/view', report.id]);
    }


    getTime(time) {
        return moment(time, 'HH:mm').format('hh:mmA');
    }

    getReportFields(fields) {
        return JSON.parse(fields);
    }

    canViewReport(start_at, end_at) {
        const time = new Date().getTime();
        const currentTime = moment(time).format('HH:m:s:sss');

        // console.log(currentTime, start_at, end_at);

        if (currentTime > start_at && currentTime < end_at) {
            return true;
        }

        return false;
    }

}

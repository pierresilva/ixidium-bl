import {Component, Input, OnInit} from '@angular/core';

@Component({
    selector: 'renova-report-filter',
    templateUrl: './report-filter.component.html',
    styleUrls: ['./report-filter.component.scss']
})
export class ReportFilterComponent implements OnInit {

    title: any;
    data: any;
    type: any;
    connection: any;

    @Input() set titleIn(title) {
        this.title = title;
    }

    @Input() set dataIn(data) {
        this.data = data;
    }

    @Input() set typeIn(type) {
        this.type = type;
    }

    @Input() set connectionIn(connection) {
        this.connection = connection;
    }

    constructor() {
    }

    ngOnInit() {
    }

}

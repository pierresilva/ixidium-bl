import {AfterViewInit, Component, OnInit, ViewChild} from '@angular/core';

import {DataTableDirective} from 'angular-datatables';

@Component({
    selector: 'renova-data-tables-sample',
    templateUrl: './data-tables-sample.component.html',
    styleUrls: ['./data-tables-sample.component.scss']
})
export class DataTablesSampleComponent implements OnInit, AfterViewInit {
    @ViewChild(DataTableDirective)
    datatableElement: DataTableDirective;

    dtOptions: DataTables.Settings = {};

    constructor() {
    }

    ngOnInit(): void {
        this.dtOptions = {
            ajax: 'data/data.json',
            columns: [{
                title: 'ID',
                data: 'id'
            }, {
                title: 'First name',
                data: 'firstName'
            }, {
                title: 'Last name',
                data: 'lastName'
            }]
        };
    }

    ngAfterViewInit(): void {
        this.datatableElement.dtInstance.then((dtInstance: DataTables.Api) => {
            dtInstance.columns().every(function () {
                const that = this;
                $('input', this.footer()).on('keyup change', function () {
                    if (that.search() !== this['value']) {
                        that
                            .search(this['value'])
                            .draw();
                    }
                });
            });
        });
    }

}

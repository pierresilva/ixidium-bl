import {AfterViewInit, Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {Router, ActivatedRoute} from '@angular/router';
import {ApiService} from '../../../shared/services/api.service';
import {MzToastService} from '@ngx-materialize';
import {FormBuilder, FormGroup, Validators, AbstractControl, FormControl, FormArray} from '@angular/forms';
import {Report} from '../models/report';

declare var $: any;
declare var M: any;
import * as moment from 'moment';

@Component({
    selector: 'renova-reports-form',
    templateUrl: './reports-form.component.html',
    styleUrls: ['./reports-form.component.scss']
})
export class ReportsFormComponent implements OnInit, AfterViewInit {
    reportId: number;
    reportTitle: string;
    report: Report;

    modalMode: false;

    selectModel: any;
    textModel: any;
    viewFields: any[];
    modulesList: any[];
    connectionList: any[];

    fileUrl = '';

    reportsForm: FormGroup;
    fieldsArray: FormArray;

    errorMessageResources = {
        name: {
            required: 'Este campo es requerido.',
        },
        module: {
            required: 'Este campo es requerido.',
        },
        connection: {
            required: 'Este campo es requerido.',
        },
        description: {
            required: 'Este campo es requerido.',
        },
        start_at: {
            required: 'Este campo es requerido.',
        },
        end_at: {
            required: 'Este campo es requerido.',
        },
        sql: {
            required: 'Este campo es requerido.',
        },
        fields: {
            required: 'Este campo es requerido.',
        },
        field: {
            required: 'Este campo es requerido.',
        },
        title: {
            required: 'Este campo es requerido.',
        },
        filter: {
            required: 'Este campo es requerido.',
        },
        options: {
            required: 'Este campo es requerido.',
        },
        totalize: {
          required: 'Este campo es requerido.',
        },
    };

    public timepickerOptions: any = {
        default: 'now', // Set default time: 'now', '1:30AM', '16:30'
        fromnow: 0,       // set default time to * milliseconds from now (using with default = 'now')
        twelvehour: true, // Use AM/PM or 24-hour format
        donetext: 'OK', // text for done-button
        cleartext: 'Limpiar', // text for clear-button
        canceltext: 'Cancelar', // Text for cancel-button
        autoclose: true, // automatic close timepicker
        ampmclickable: true, // make AM PM clickable
        aftershow: () => alert('AfterShow has been invoked.'), // function for after opening timepicker
    };

    constructor(private api: ApiService,
                private route: ActivatedRoute,
                private router: Router,
                private toast: MzToastService,
                private fb: FormBuilder) {
    }

    @Input() set modal(modal) {
        this.modalMode = modal;
    }

    @Input() set reportIn(report) {
        this.report = report;
    }

    @Output() reportOut = new EventEmitter();

    ngAfterViewInit() {
    }

    ngOnInit() {

        this.report = new Report;

        this.viewFields = [];
        this.selectModel = null;
        this.textModel = null;

        this.modulesList = [];
        this.connectionList = [];

        this.route.params.subscribe(params => {
            this.reportId = +params['reportId'];
        });

        if (this.reportId) {
            this.reportTitle = 'Editar Reporte';
            this.getReport();
        } else {
            this.reportTitle = 'Nuevo Reporte';
        }

        this.getModulesList();
        this.getConnectionList();

        this.buildForm();

    }

    buildForm() {
        this.fieldsArray = new FormArray([]);

        this.reportsForm = this.fb.group({
            name: [
                this.report.name,
                Validators.compose([
                    Validators.required,
                ])
            ],
            module: [
                this.report.module,
                Validators.compose([])
            ],
            connection: [
                this.report.connection,
                Validators.compose([
                    Validators.required,
                ])
            ],
            description: [
                this.report.description,
                Validators.compose([
                    Validators.required,
                ])
            ],
            start_at: [
                this.report.start_at,
                Validators.compose([
                    Validators.required,
                ])
            ],
            end_at: [
                this.report.end_at,
                Validators.compose([
                    Validators.required,
                ])
            ],
            sql: [
                this.report.sql,
                Validators.compose([
                    Validators.required,
                ])
            ],
            fields: this.fieldsArray,
        });
    }

    initField(name: string = '', title: string = '', filter: string = '', totalize: string = '') {
        return this.fb.group({
            name: [name, Validators.required],
            title: [title, Validators.required],
            filter: [filter, Validators.required],
            totalize: [totalize],
        });
    }

    saveReport() {
        let data;
        data = this.reportsForm.value;
        if (this.reportId) {
            this.api.put('reporter/reports/' + this.reportId, data)
                .subscribe(res => {
                    this.toast.show(
                        '' + res.message,
                        5000,
                        'green'
                    );
                });
        } else {
            this.api.post('reporter/reports', data)
                .subscribe(res => {
                    this.toast.show(
                        '' + res.message,
                        5000,
                        'green'
                    );
                });
        }


    }

    getReport() {
        if (this.reportId) {
            this.api.get('reporter/reports/' + this.reportId)
                .subscribe(res => {
                    this.report = res.data;
                    this.report.start_at = moment(res.data.start_at, 'HH:mm').format('hh:mmA');
                    this.report.end_at = moment(res.data.end_at, 'HH:mm').format('hh:mmA');
                    this.viewFields = JSON.parse(res.data.fields);
                    setTimeout(() => {
                        for (let i = 0; i < this.viewFields.length; i++) {

                            const control = <FormArray>this.fieldsArray;
                            control.push(this.initField(
                                '' + this.viewFields[i].name,
                                '' + this.viewFields[i].title,
                                '' + this.viewFields[i].filter
                            ));
                        }

                        M.updateTextFields();
                    }, 200);
                });
        }

    }

    getModulesList() {
        this.api.get('reporter/connections')
            .subscribe(
                res => {
                    for (const connectionName in res.data) {
                        if (true) {
                            this.connectionList.push({
                                text: connectionName,
                                value: connectionName,
                            });
                        }
                    }
                }
            );
    }

    getConnectionList() {
        this.api.get('reporter/modules')
            .subscribe(
                res => {
                    for (const moduleName in res.data) {
                        if (true) {
                            this.modulesList.push({
                                text: moduleName,
                                value: moduleName,
                            });
                        }
                    }
                }
            );
    }

    checkSql() {

        this.clearFormArray();

        this.api.post('reporter/check-sql', {
            connection: $('#report-connection').val(),
            sql: $('#report-sql').val(),
        }).subscribe(res => {
            this.toast.show(
                res.message,
                5000,
                'green'
            );

            setTimeout(() => {
                const that = this;
                this.viewFields = [];
                for (const index in res.data) {
                    if (true) {
                        const control = <FormArray>this.fieldsArray;
                        control.push(this.initField('' + index, '' + index, 'string'));
                        that.viewFields.push({
                            name: '' + index,
                            title: '' + index,
                            filter: 'string',
                        });
                    }
                }
            }, 200);
        });
    }

    clearFormArray() {
        while (this.fieldsArray.length !== 0) {
            this.fieldsArray.removeAt(0);
        }
    }

    sendReport() {
        this.reportOut.emit(this.report);
        $('.modal').modal('close');
        this.reportsForm.reset();
    }
}

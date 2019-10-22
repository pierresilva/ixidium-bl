import {Component, OnInit, ViewEncapsulation} from '@angular/core';
import {ApiService} from '../../../shared/services/api.service';
import {AlertService} from '../../../shared/services/alert.service';
import {MzToastService} from '@ngx-materialize';

@Component({
    selector: 'renova-models',
    templateUrl: './models.component.html',
    styleUrls: ['./models.component.scss'],
    encapsulation: ViewEncapsulation.None,
})
export class ModelsComponent implements OnInit {

    modelOptions: any[];
    modelsList: any[];
    model: any;

    constructor(private api: ApiService,
                private alert: AlertService,
                private toast: MzToastService) {
    }

    ngOnInit() {
        this.getData();
    }

    addModel() {
        this.alert.confirmThis(
            'confirm',
            'Agregar Modelo?',
            'Desea dar seguimiento de actividades al modelo ' + this.model + '?',
            () => {
                this.api.post('audit/activities/models', {fqn: this.model})
                    .subscribe(
                        resp => {
                            this.toast.show(
                                resp.message,
                                5000,
                                'green',
                            );

                            this.getData();
                        }
                    );
            },
            () => {

            }
        );
    }

    removeModel(id) {
        this.alert.confirmThis(
            'confirm',
            'Dejar de auditar?',
            'Desea detener el seguimiento de actividades al modelo ' + this.model + '?',
            () => {
                this.api.delete('audit/activities/models/' + id, {})
                    .subscribe(
                        resp => {
                            this.toast.show(
                                resp.message,
                                5000,
                                'green',
                            );

                            this.getData();
                        }
                    );
            },
            () => {

            }
        );
    }

    getData() {
        this.api.get('audit/activities/get-models-list').subscribe(
            res => {
                this.modelOptions = res.data;
            }
        );
        this.api.get('audit/activities/models').subscribe(
            res => {
                this.modelsList = res.data;
            }
        );
    }

}

import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {OnlineHelp} from '../models/online-help';
import {MzToastService} from '@ngx-materialize';
import {ApiService} from '../../../shared/services/api.service';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';

declare var $: any;

@Component({
    selector: 'renova-online-helps-form',
    templateUrl: './online-helps-form.component.html',
    styleUrls: ['./online-helps-form.component.scss']
})
export class OnlineHelpsFormComponent implements OnInit {

    onlineHelp: OnlineHelp;
    modalMode: false;
    onlineHelpForm: FormGroup;

    errorMessageResources = {
        url_form: {
            required: 'Este campo es requerido.',
        },
        element_id: {
            required: 'Este campo es requerido.',
        },
        description: {
            required: 'Este campo es requerido.',
        },
        status: {
            required: 'Este campo es requerido.',
        },
        position: {
            required: 'Este campo es requerido.',
        }
    };

    constructor(private api: ApiService,
                private toast: MzToastService,
                private formBuilder: FormBuilder) {
    }

    @Input() set modal(modal) {
        this.modalMode = modal;
    }

    @Input() set onlineHelpIn(onlineHelp) {
        this.onlineHelp = onlineHelp;
    }

    @Output() onlineHelpOut = new EventEmitter();

    ngOnInit() {
        console.log(this.onlineHelp);
        //this.onlineHelpForm.reset();
        this.buildForm();
    }

    buildForm() {
        this.onlineHelpForm = this.formBuilder.group({
            url_form: [
                this.onlineHelp.url_form,
                Validators.compose([
                    Validators.required,
                ])
            ],
            element_id:
                [
                    this.onlineHelp.element_id,
                    Validators.compose([
                        Validators.required,
                    ])
                ],
            description:
                [
                    this.onlineHelp.description,
                    Validators.compose([
                        Validators.required,
                    ])
                ],
            status:
                [
                    this.onlineHelp.status,
                    Validators.compose([
                        Validators.required,
                    ])
                ],
            position:
                [
                    this.onlineHelp.position,
                    Validators.compose([
                        Validators.required,
                    ])
                ]
        })
        ;
    }

    sendOnlineHelp() {
        this.onlineHelpOut.emit(this.onlineHelp);
    }

    saveOnlineHelp() {

        if (!this.onlineHelpForm.valid) {
            this.toast.show(
                'Existen errores en el formulario!',
                5000,
                'red',
            );
            return;
        }

        if (this.onlineHelp.id) {
            this.api.put('administration/online-help/' + this.onlineHelp.id, this.onlineHelp)
                .subscribe(
                    () => {
                        this.toast.show(
                            'Ayuda editada con éxito!',
                            5000,
                            'green'
                        );
                        $('.modal').modal('close');
                        this.sendOnlineHelp();
                    }
                );
        } else {
            this.api.post('administration/online-help', this.onlineHelp)
                .subscribe(
                    () => {
                        this.toast.show(
                            'Ayuda creada con éxito!',
                            5000,
                            'green'
                        );
                        $('.modal').modal('close');
                        this.sendOnlineHelp();
                    }
                );
        }
    }

}

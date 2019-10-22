import {Component, OnInit} from '@angular/core';
import {ApiService} from '../../shared/services/api.service';

declare var $: any;
declare var M: any;
import * as moment from 'moment';
import {MzToastService} from '@ngx-materialize';

@Component({
    selector: 'renova-pages',
    templateUrl: './pages.component.html',
    styleUrls: ['./pages.component.scss']
})

export class PagesComponent implements OnInit {
    message: any;
    image: any;
    base64: any;

    constructor(private api: ApiService,
                private toast: MzToastService) {
    }

    ngOnInit() {
        this.image = '';
        this.base64 = '';
        this.api.get('pages')
            .subscribe(
                data => {
                    this.message = data.message;
                }
            );
    }

    uploadImage() {
        if (this.image === '' || this.base64 === '') {
            this.toast.show(
                'Debe seleccionar una imágen!',
                5000,
                'red'
            );

            return;
        }

        this.api.post('pages/upload/image', {'file_name': this.image, 'file_base64': this.base64})
            .subscribe((res) => {
                this.toast.show(
                    '' + res.message,
                    5000,
                    'green'
                );
            });
    }

    setImage(event) {
        console.log(event);
        this.image = event.file.name;
        this.base64 = event.source;
    }

}

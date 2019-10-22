import {Component, OnInit} from '@angular/core';
import {ApiService} from '../../../shared/services/api.service';
import {OnlineHelp} from '../models/online-help';
import {AlertService} from '../../../shared/services/alert.service';
import {MzToastService} from '@ngx-materialize';
import {PaginationMeta} from '../../../shared/layout/pagination/models/pagination-meta';

@Component({
    selector: 'renova-online-helps-list',
    templateUrl: './online-helps-list.component.html',
    styleUrls: ['./online-helps-list.component.scss']
})
export class OnlineHelpsListComponent implements OnInit {

    meta: PaginationMeta;
    /**
     * Listado de ayudas
     */
    onlineHelps: OnlineHelp[];
    onlineHelpsOrder: OnlineHelp[];

    onlineHelp: OnlineHelp;

    constructor(private api: ApiService,
                private alert: AlertService,
                private toast: MzToastService) {
    }

    ngOnInit() {
        this.onlineHelps = [];
        this.onlineHelpsOrder = [];
        this.onlineHelp = new OnlineHelp;
        this.meta = new PaginationMeta;
        this.getOnlineHelps();
    }

    getOnlineHelps(page = 1, search = $('#search-online-help').val()) {
        this.api.get('administration/online-help?page=' + page + '&search=' + search + '&paginate=true')
            .subscribe(
                resp => {
                    this.onlineHelps = resp.data;
                    this.meta = resp.meta;
                }
            );
    }

    newOnlineHelp() {
        this.onlineHelp = new OnlineHelp;
    }

    getOnlineHelpByUrlForm(url_form: any) {
        this.api.get('administration/online-help?page=1&search=' + url_form + '&paginate=false')
            .subscribe(
                resp => {
                    this.onlineHelpsOrder = resp.data;
                }
            );
    }

    editOnlineHelp(onlineHelp: OnlineHelp) {
        this.onlineHelp = onlineHelp;
    }

    deleteOnlineHelp(onlineHelp: OnlineHelp) {
        this.alert.confirmThis(
            'confirm',
            'Eliminar Ayuda',
            '¿Desea eliminar la ayuda del formulario [' + onlineHelp.url_form + '] para el elemento con id [' + onlineHelp.element_id + '] ?',
            () => {
                this.api.delete('administration/online-help/' + onlineHelp.id, {})
                    .subscribe(
                        () => {
                            this.toast.show(
                                'Ayuda eliminada con éxito!',
                                5000,
                                'green',
                            );
                            this.getOnlineHelps();
                        }
                    );
            },
            () => {
                this.toast.show(
                    'Eliminación cancelada!',
                    5000,
                    'orange',
                );
            },
            'red white-text');
    }
}

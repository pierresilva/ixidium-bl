import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {OnlineHelp} from '../models/online-help';
import {ApiService} from '../../../shared/services/api.service';
import {DragulaService} from 'ng2-dragula';
import {MzToastService} from '@ngx-materialize';

declare var $: any;

@Component({
    selector: 'renova-online-help-order',
    templateUrl: './online-help-order.component.html',
    styleUrls: ['./online-help-order.component.scss']
})
export class OnlineHelpOrderComponent implements OnInit {

    modalMode: false;
    onlineHelps: OnlineHelp[];

    constructor(private api: ApiService,
                private dragulaService: DragulaService,
                private toast: MzToastService) {

    }

    @Input() set modal(modal) {
        this.modalMode = modal;
    }

    @Input() set onlineHelpOrderIn(onlineHelpsOrder) {
        this.onlineHelps = onlineHelpsOrder;

        this.dragulaService = new DragulaService;
        this.dragulaService.setOptions('sixth-bag', {
            moves: function (el, container, handle) {
                return handle.classList.contains('handle');
            }
        });

    }

    @Output() onlineHelpOut = new EventEmitter();

    ngOnInit() {
        this.onlineHelps = [];
    }

    sendOnlineHelp() {
        this.onlineHelpOut.emit(this.onlineHelps);
    }

    saveOrderOnlineHelp() {
        this.api.put('administration/update-order-online-help', this.onlineHelps)
            .subscribe(
                () => {
                    this.toast.show(
                        'Ayudas ordenadas con éxito!',
                        5000,
                        'green'
                    );
                    $('.modal').modal('close');
                    this.sendOnlineHelp();
                }
            );
    }
}

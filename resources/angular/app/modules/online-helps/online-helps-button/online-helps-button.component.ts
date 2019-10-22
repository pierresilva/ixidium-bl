import {Component, OnInit} from '@angular/core';
import {OnlineHelpIntroJs} from '../models/online-help-intro-js';
import {ApiService} from '../../../shared/services/api.service';
import {Router} from '@angular/router';
import {OnlineHelpService} from '../../../shared/services/online-help.service';

declare var introJs: any;

@Component({
    selector: 'renova-online-helps-button',
    templateUrl: './online-helps-button.component.html',
    styleUrls: ['./online-helps-button.component.scss']
})
export class OnlineHelpsButtonComponent implements OnInit {

    help: any;
    onlineHelp: OnlineHelpIntroJs;
    onlineHelps: OnlineHelpIntroJs[];
    introJs: any;

    constructor(private api: ApiService,
                private router: Router,
                private onlineHelpService: OnlineHelpService) {

        this.onlineHelp = new OnlineHelpIntroJs;
        this.onlineHelps = [];
    }

    ngOnInit() {
        this.api.get('administration/online-help?page=1&search=' + this.router.url + '&paginate=false')
            .subscribe(
                resp => {

                    this.help = resp.data.length > 0 ? true : false;

                    /*this.onlineHelpService.helpSubject.next(resp.data.length > 0 ? true : false);
                    this.onlineHelpService.helpSubject.subscribe(respHelp => {
                        this.help = respHelp;
                    });*/

                    if (this.help) {
                        for (let i = 0; i < resp.data.length; i++) {
                            this.onlineHelp = new OnlineHelpIntroJs();

                            this.onlineHelp.element = '#' + resp.data[i].element_id;
                            this.onlineHelp.intro = resp.data[i].description;
                            this.onlineHelp.position = 'right';

                            this.onlineHelps.push(this.onlineHelp);
                        }
                    }
                }
            );

        $('.intro-js-button').on('click', function () {
            let containerClosest;
            containerClosest = $(this).closest('div.modal').prop('id');
            setTimeout(function () {
                $('.introjs-overlay, .introjs-helperLayer, .introjs-tooltipReferenceLayer').appendTo('#' + containerClosest);
            }, 0);
        });
    }

    /**
     * Permite visualizar la ayuda online
     */
    showHelp() {
        this.introJs = introJs();
        this.introJs.addSteps(this.onlineHelps);
        this.introJs.start();
    }
}

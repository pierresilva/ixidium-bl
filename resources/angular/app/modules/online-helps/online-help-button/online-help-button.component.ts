import {Component, OnInit} from '@angular/core';
import {ApiService} from '../../../shared/services/api.service';
import {ActivatedRoute, Router} from '@angular/router';
import {AnnoJsItem} from '../models/anno-js-item';

declare var $: any;
declare var Anno: any;

@Component({
    selector: 'renova-online-help-button',
    templateUrl: './online-help-button.component.html',
    styleUrls: ['./online-help-button.component.scss']
})

export class OnlineHelpButtonComponent implements OnInit {
    help: boolean;
    annoJsItems: any;
    helpItems: any;

    constructor(private api: ApiService,
                private route: ActivatedRoute,
                private router: Router) {
    }

    ngOnInit() {
        this.api.get('administration/online-help?search=' + this.router.url + '&paginate=false')
            .subscribe(
                resp => {

                    this.help = resp.data.length;
                    this.annoJsItems = resp.data;

                    if (this.help) {
                        let items;
                        items = [];

                        for (let i = 0; i < this.annoJsItems.length; i++) {
                            let item: AnnoJsItem;
                            item = new AnnoJsItem;
                            item.target = '#' + this.annoJsItems[i].element_id;
                            item.content = this.annoJsItems[i].description;

                            if (this.annoJsItems[i].show_overlay === null || this.annoJsItems[i].show_overlay === undefined) {
                                item.showOverlay = function () {
                                };
                            } else {
                                item.showOverlay = this.annoJsItems[i].show_overlay;
                            }

                            if (i === 0) {
                                item.buttons = [
                                    {
                                        text: 'Siguiente',
                                        click: function (anno, evt) {
                                            anno.switchToChainNext();
                                        }
                                    }
                                ];
                            } else if (i === this.annoJsItems.length - 1) {
                                item.buttons = [
                                    {
                                        text: 'Anterior',
                                        className: 'anno-btn-low-importance',
                                        click: function (anno, evt) {
                                            anno.switchToChainPrev();
                                        }
                                    },
                                    {
                                        text: 'Entendido',
                                        click: function (anno, evt) {
                                            // anno.chainIn();
                                            anno.hide();
                                        }
                                    }
                                ];
                            } else {
                                item.buttons = [
                                    {
                                        text: 'Anterior',
                                        className: 'anno-btn-low-importance',
                                        click: function (anno, evt) {
                                            anno.switchToChainPrev();
                                        }
                                    },
                                    {
                                        text: 'Siguiente',
                                        click: function (anno, evt) {
                                            anno.switchToChainNext();
                                        }
                                    }
                                ];
                            }

                            if (this.annoJsItems[i].position) {
                                item.position = this.annoJsItems[i].position;
                            }
                            items.push(item);
                        }

                        this.helpItems = items;
                    }

                  console.log(this.helpItems);
                }
            );
    }

    /**
     * Permite visualizar la ayuda online
     */
    showHelp() {

        console.log(this.helpItems);

        let annoJs = new Anno(this.helpItems);

        annoJs.show();
    }
}

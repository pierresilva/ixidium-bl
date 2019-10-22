import { AfterViewInit, Component, OnInit } from '@angular/core';
import { environment } from '../../../../environments/environment';
import { ActivatedRoute, Router } from '@angular/router';

declare var $: any;
declare var jQuery: any;

@Component({
    selector: 'renova-elfinder',
    templateUrl: './elfinder.component.html',
    styleUrls: ['./elfinder.component.scss']
})
export class ElfinderComponent implements OnInit, AfterViewInit {

    fieldId: string;

    constructor(private route: ActivatedRoute,
                private router: Router) {
    }

    ngOnInit() {
        this.route.params.subscribe(params => {
            this.fieldId = params['fieldId'];
        });
    }

    ngAfterViewInit() {

        let elf;
        let that;
        that = this;
        elf = $('#elfinder').elfinder({
            // set your elFinder options here
            lang: 'es', // locale
            customData: {
                _token: ''
            },
            url: environment.site_url + 'elfinder/connector?_token=&cmd=open&init=1&tree=1',  // connector URL
            soundPath: environment.site_url + 'packages/barryvdh/elfinder/sounds',
            dialog: { width: 900, modal: true, title: 'Select a file' },
            resizable: false,
            commandsOptions: {
                getfile: {
                    oncomplete: 'destroy'
                }
            },

            getFileCallback: function (file) {
                // @ts-ignore
                // window.parent.processSelectedFile(file.path, that.fieldId);
                // @ts-ignore
                // parent.jQuery.colorbox.close();
            }
        }).elfinder('instance');
    }

}

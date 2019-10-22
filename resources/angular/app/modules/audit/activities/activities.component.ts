import {Component, OnInit} from '@angular/core';
import {ApiService} from '../../../shared/services/api.service';
import {ActivatedRoute} from '@angular/router';
import {PaginationMeta} from '../../../shared/layout/pagination/models/pagination-meta';

declare var $: any;

@Component({
    selector: 'renova-activities',
    templateUrl: './activities.component.html',
    styleUrls: ['./activities.component.scss']
})

export class ActivitiesComponent implements OnInit {
    objectKeys = Object.keys;
    model: any;
    activities: any[];
    meta: PaginationMeta;

    constructor(private api: ApiService,
                private route: ActivatedRoute) {
    }

    ngOnInit() {
        this.meta = new PaginationMeta;
        this.route.paramMap.subscribe(params => {
            this.model = params.get('model');
        });

        this.getData();
    }

    toggleTr(event, id) {
        $('#changes-' + id ).toggleClass('hidden', 'shown');
    }

    getData(page = 1, search = $('#search-activity').val()) {
        this.api.get('audit/activities' + (this.model ? '?page=' + page + '&search=' + this.model : '?page=' + page + '&search=' + search))
            .subscribe(
                resp => {
                    this.activities = resp.data;
                    this.meta = resp.meta;
                    this.getDiffs();
                }
            );
    }

    getDiffs() {

        for (let i = 0; i < this.activities.length; i++) {
            if (this.activities[i].properties.old) {
                let before = this.activities[i].properties.old;
                let after = this.activities[i].properties.attributes;

                let diffs = this.objectDiff(before, after, {});

                this.activities[i].differences = diffs;
            } else {
                this.activities[i].differences = null;
            }
        }

    }

    objectDiff(a, b, c) {
        c = {};
        const that = this;
        $.each([a, b], function (index, obj) {
            for (let prop in obj) {
                if (obj.hasOwnProperty(prop)) {
                    if (typeof obj[prop] === 'object' && obj[prop] !== null) {
                        c[prop] = that.objectDiff(a[prop], b[prop], c);
                    } else {
                        if (a === undefined) {
                            a = {};
                        }
                        if (b === undefined) {
                            b = {};
                        }
                        if (a[prop] !== b[prop]) {
                            c[prop] = [a[prop], b[prop]];
                        }
                    }
                }
            }
        });
        return c;
    }

}

import {Component, Input, OnInit} from '@angular/core';
import {ConfigSelectAll} from './config-select-all';
import {ObjectDataSelectAll} from './object-data-select-all';

@Component({
    selector: 'renova-select-all',
    templateUrl: './select-all.component.html',
    styleUrls: ['./select-all.component.scss']
})
export class SelectAllComponent implements OnInit {

    valueSelected = [];
    dataConfig = new ConfigSelectAll;
    tempDataConfig = new ConfigSelectAll;
    data: ObjectDataSelectAll[] = [];

    constructor() {
    }

    ngOnInit() {
    }

    @Input() set dataIn(data) {
        const inOptions = [];
        if (data) {
            for (let i = 0; i < data.length; i++) {
                inOptions[i] = data[i];
            }
        }
        this.data = inOptions;
    }

    @Input() set configIn(obj: ConfigSelectAll) {
        this.dataConfig = Object.assign(this.tempDataConfig, obj);
    }

    selectAll(event) {
        this.valueSelected = [];
        const isCheked = event.target.checked;

        if (isCheked) {
            for (let i = 0; i < this.data.length; i++) {
                const obj = this.data[i];
                this.valueSelected.push(obj.id.toString());
            }
        }

    }

}

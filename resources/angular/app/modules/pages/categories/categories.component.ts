import {Component, OnInit} from '@angular/core';
import {MzModalService} from '@ngx-materialize';

@Component({
    selector: 'renova-categories',
    templateUrl: './categories.component.html',
    styleUrls: ['./categories.component.scss']
})

export class CategoriesComponent implements OnInit {

    constructor() {
    }

    ngOnInit() {
    }

    newCategory() {
        console.log('new BlogCategory');
    }

    saveCategory(event) {
        console.log(event);
    }

}

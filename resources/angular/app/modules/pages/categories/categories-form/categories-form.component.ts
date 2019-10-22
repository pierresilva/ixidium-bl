import {Component, EventEmitter, Input, OnInit, Output, ViewEncapsulation} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {Category} from '../models/category';
import {MzToastService} from '@ngx-materialize';
import {ApiService} from '../../../../shared/services/api.service';
import {MzDatepickerOptions} from '../../../../shared/clasess/mz-datepicker-options';

declare var $: any;

@Component({
    selector: 'renova-categories-form',
    templateUrl: './categories-form.component.html',
    styleUrls: ['./categories-form.component.scss'],
    encapsulation: ViewEncapsulation.None,
})

export class CategoriesFormComponent implements OnInit {
    category: Category;
    modalMode: false;
    datepickerOptions = new MzDatepickerOptions().datepickerOptions;
    categoriesForm: FormGroup;
    categories: any;
    autocomplete: { data: any };


    errorMessageResources = {
        title: {
            required: 'Este campo es requerido.',
        },
        slug: {
            required: 'Este campo es requerido.',
        },
        description: {
            required: 'Este campo es requerido.',
        },
        category_id: {},
    };

    constructor(private api: ApiService,
                private formBuilder: FormBuilder,
                private toast: MzToastService) {
    }

    @Input() set modal(modal) {
        this.modalMode = modal;
    }

    @Input() set categoryIn(category) {
        this.category = category;
    }

    @Output() categoryOut = new EventEmitter();

    ngOnInit() {
        this.categories = [];
        this.buildForm();
        this.getCategories();

    }

    buildForm() {
        this.categoriesForm = this.formBuilder.group({
            title: [
                this.category.title,
                Validators.compose([
                    Validators.required,
                ])
            ], slug: [
                this.category.slug,
                Validators.compose([
                    Validators.required,
                ])
            ],
            description: [
                this.category.description,
                Validators.compose([
                    Validators.required,
                ])
            ], category_id: [
                this.category.category_id,
                Validators.compose([])
            ],
        });
    }

    sendCategory() {
        this.categoryOut.emit(this.category);
        $('.modal').modal('close');
        this.categoriesForm.reset();
    }

    saveCategory() {
        if (this.category.id) {
            this.api.put('pages/categories/' + this.category.id, this.category)
                .subscribe(
                    () => {
                        this.toast.show(
                            'Item actualizado con éxito!',
                            5000,
                            'green',
                        );
                        this.sendCategory();
                    }
                );
        } else {
            this.api.post('pages/categories', this.category)
                .subscribe(
                    () => {
                        this.toast.show(
                            'Item creado con éxito!',
                            5000,
                            'green',
                        );
                        this.sendCategory();
                    }
                );
        }
    }

    getCategories() {
        this.api.get('pages/categories?search=getMenu')
            .subscribe(
                resp => {
                    this.categories = resp.data;
                }
            );
    }

    setCategoryCategoryId(value) {
        this.category.category_id = value.value;
    }

}


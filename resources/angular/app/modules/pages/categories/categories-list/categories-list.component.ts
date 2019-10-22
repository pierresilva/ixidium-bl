import {Component, OnInit, ViewChild, ViewEncapsulation} from '@angular/core';
import {ApiService} from '../../../../shared/services/api.service';
import {Category} from '../models/category';
import {AlertService} from '../../../../shared/services/alert.service';
import {MzToastService} from '@ngx-materialize';
import {PaginationMeta} from '../../../../shared/layout/pagination/models/pagination-meta';
import {CategoriesFormComponent} from '../categories-form/categories-form.component';

declare var $: any;

@Component({
    selector: 'renova-categories-list',
    templateUrl: './categories-list.component.html',
    styleUrls: ['./categories-list.component.scss'],
    encapsulation: ViewEncapsulation.None,
})

export class CategoriesListComponent implements OnInit {
    @ViewChild(CategoriesFormComponent) private categoriesForm: CategoriesFormComponent;
    meta: PaginationMeta;
    /**
     * Listado de Categorías
     */
    categories: Category[];

    /**
     * Item de Categorías
     */
    category: Category;

    constructor(private api: ApiService,
                private alert: AlertService,
                private toast: MzToastService) {
    }

    ngOnInit() {
        this.categories = [];
        this.category = new Category;
        this.meta = new PaginationMeta;
        this.getCategories();
        $('.modal').modal();
    }

    /**
     * Obtiene las Categorías paginadas
     *
     * @param {number} page
     * @param {string | number | string[]} search
     */
    getCategories(page = 1, search = $('#search-categories').val()) {
        console.log(search);
        this.api.get('pages/categories?page=' + page + '&search=' + search)
            .subscribe(
                resp => {
                    this.categories = resp.data;
                    this.meta = resp.meta;
                }
            );
        this.categoriesForm.getCategories();
    }

    /**
     * Crea un objeto vacio Categorías
     */
    newCategory() {
        this.category = new Category;
    }

    /**
     * Obtiene el objeto Categorías para su edición
     *
     * @param {Category} category
     */
    editCategory(category: Category) {
        this.category = category;
    }

    /**
     * Obtiene el objeto Categorías para su eliminación
     *
     * @param {Category} category
     */
    deleteCategory(category: Category) {
        console.log(category);
        this.alert.confirmThis(
            'confirm',
            'Eliminar Categorías?',
            'Desea eliminar la Categoría ?',
            () => {
                this.api.delete('pages/categories/' + category.id, {})
                    .subscribe(
                        () => {
                            this.toast.show(
                                'Categoría eliminada con éxito!',
                                5000,
                                'green',
                            );
                            this.getCategories();
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
            'orange white-text');
    }

}

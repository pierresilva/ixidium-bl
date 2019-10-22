import {AfterViewInit, Component, Input, OnInit, ViewChild, Optional, Inject, ElementRef, NgZone} from '@angular/core';
import {
    NgModel,
    NG_VALUE_ACCESSOR,
    NG_VALIDATORS,
    NG_ASYNC_VALIDATORS,
} from '@angular/forms';
import {ElementBase} from '../element-base';
import {BehaviorSubject} from 'rxjs/BehaviorSubject';
import {Select2OptionData} from 'ng2-select2';


declare var $: any;

@Component({
    selector: 'renova-autocomplete',
    templateUrl: './autocomplete.component.html',
    styleUrls: ['./autocomplete.component.scss'],
    providers: [{
        provide: NG_VALUE_ACCESSOR,
        useExisting: AutocompleteComponent,
        multi: true,
    }]
})
export class AutocompleteComponent extends ElementBase<string> implements OnInit, AfterViewInit {
    @Input() public label: string;
    @Input() public placeholder: string;
    @Input() public selected: string;
    @Input() public name: string;
    @Input() public multiple: boolean;
    @Input() public required: boolean;

    private _values = new BehaviorSubject<any[]>([]);
    @Input() set values(values) {
        this._values.next(values);
    }

    selectOptions: Array<Select2OptionData>;

    @ViewChild(NgModel) model: NgModel;

    public identifier = `autocomplete-${++identifier}`;

    options: Select2Options;

    constructor(@Optional() @Inject(NG_VALIDATORS) validators: Array<any>,
                @Optional() @Inject(NG_ASYNC_VALIDATORS) asyncValidators: Array<any>,
                private el: ElementRef,
                private zone: NgZone) {
        super(validators, asyncValidators);
    }

    ngOnInit() {
        this.selectOptions = [];
        this._values
        // .takeWhile(() => !this.selectOptions) // unsubscribe once groupPosts has value
            .subscribe((values) => {
                this.selectOptions = [];
                if (values.length > 0) {
                    for (let i = 0; i < values.length; i++) {
                        let option = {
                            text: '',
                            id: '',
                        };

                        option.id = values[i].id;
                        if (!!values[i].title) {
                            option.text = values[i].title;
                        } else if (!!values[i].name) {
                            option.text = values[i].name;
                        } else {
                            option.text = 'Not set!';
                        }

                        this.selectOptions.push(option);
                    }
                }

            });

        this.options = {
            multiple: this.multiple,
            closeOnSelect: true,
            placeholder: {
                id: null, // the value of the option
                text: this.placeholder,
            },
            allowClear: !this.required,
            language: 'es'
        };
    }

    ngAfterViewInit() {

    }

    changeValue(event) {
        this.value = event.value;
    }

}

let identifier = 0;

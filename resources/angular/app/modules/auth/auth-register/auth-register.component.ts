import {Component, OnDestroy, OnInit} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {Router} from '@angular/router';
import {AuthService} from '../../../shared/services/auth.service';
import {ApiService} from '../../../shared/services/api.service';

declare var $: any;

@Component({
    selector: 'renova-auth-register',
    templateUrl: './auth-register.component.html',
    styleUrls: ['./auth-register.component.scss']
})
export class AuthRegisterComponent implements OnInit, OnDestroy {

    errorMessageResources = {
        name: {
            required: 'Requerido.',
            minlength: 'Debe contener al menos 5 caracteres.',
            maxlength: 'Debe contener máximo 16 caracteres.',
        },
        email: {
            required: 'Requerido.',
            email: 'Debe contener un email valido.',
        },
        password: {
            required: 'Requerido.',
            minlength: 'Debe contener al menos 5 caracteres.',
            maxlength: 'Debe contener máximo 16 caracteres.',
        },
        password_confirmation: {
            required: 'Requerido.',
            minlength: 'Debe contener al menos 5 caracteres.',
            maxlength: 'Debe contener máximo 16 caracteres.',
        }
    };

    registerForm: FormGroup;

    submitted = false;

    user = {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    };

    constructor(private api: ApiService,
                private auth: AuthService,
                private formBuilder: FormBuilder,
                private router: Router) {
    }

    ngOnInit() {
        $('renova-header').addClass('hidden');
        $('renova-footer').addClass('hidden');
        $('main').addClass('main-auth-page');
        this.buildForm();
    }

    ngOnDestroy() {
        $('renova-header').removeClass('hidden');
        $('renova-footer').removeClass('hidden');
        $('main').removeClass('main-auth-page');
    }

    buildForm() {
        this.registerForm = this.formBuilder.group({
            // identification
            name: [this.user.name, Validators.compose([
                Validators.required,
                Validators.minLength(4),
                Validators.maxLength(32),
            ]),
            ],
            email: [this.user.email, Validators.compose([
                Validators.required,
                Validators.email,
            ]),
            ],
            password: [this.user.password, Validators.compose([
                Validators.required,
                Validators.minLength(5),
                Validators.maxLength(16),
            ])],
            password_confirmation: [this.user.password_confirmation, Validators.compose([
                Validators.required,
                Validators.minLength(5),
                Validators.maxLength(16),
            ])],
        });
    }

    clear() {
        this.registerForm.reset();
    }

    register() {
        // this.submitted = true;
        this.user = Object.assign({}, this.registerForm.value);
        // console.log(this.user);

        this.auth.register(this.user).subscribe(
            res => {
                this.router.navigate(['/home']);
            });

    }

}

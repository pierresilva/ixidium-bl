import {Component, OnDestroy, OnInit, ViewEncapsulation} from '@angular/core';
import {ApiService} from '../../../shared/services/api.service';
import {AuthService} from '../../../shared/services/auth.service';
import {AbstractControl, FormArray, FormBuilder, FormGroup, Validators} from '@angular/forms';
import {Router} from '@angular/router';

declare var $: any;

@Component({
    selector: 'renova-auth-login',
    templateUrl: './auth-login.component.html',
    styleUrls: ['./auth-login.component.scss'],
    encapsulation: ViewEncapsulation.None,
})
export class AuthLoginComponent implements OnInit, OnDestroy {

    // error messages
    errorMessageResources = {
        email: {
            required: 'Requerido.',
            email: 'Debe contener un email valido.',
        },
        password: {
            required: 'Requerido.',
            minlength: 'Debe contener al menos 4 caracteres.',
            maxlength: 'Debe contener máximo 128 caracteres.',
        },
    };

    loginForm: FormGroup;

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
        this.loginForm = this.formBuilder.group({
            // identification
            email: [this.user.email, Validators.compose([
                Validators.required,
            ]),
            ],
            password: [this.user.password, Validators.compose([
                Validators.required,
                Validators.minLength(4),
                Validators.maxLength(128),
            ])],
        });
    }

    clear() {
        this.loginForm.reset();
    }

    login() {
        // this.submitted = true;
        this.user = Object.assign({}, this.loginForm.value);
        // console.log(this.user);

        this.auth.login(this.user).subscribe(
            res => {
                this.router.navigate(['/home']);
            });
    }

}

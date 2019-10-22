import {Component, OnDestroy, OnInit} from '@angular/core';
import {ApiService} from '../../../shared/services/api.service';
import {Router} from '@angular/router';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {AuthService} from '../../../shared/services/auth.service';

declare var $: any;

@Component({
    selector: 'renova-auth-recover-password',
    templateUrl: './auth-recover-password.component.html',
    styleUrls: ['./auth-recover-password.component.scss']
})

export class AuthRecoverPasswordComponent implements OnInit, OnDestroy {

    userEmail = '';

    recoverPasswordForm: FormGroup;

    errorMessageResources = {
        email: {
            required: 'Requerido.',
            email: 'Debe contener un email valido.',
        }
    };

    emailSend = false;

    successMessage = 'Email enviado con éxito!';

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
        this.recoverPasswordForm = this.formBuilder.group({
            // identification
            email: [this.userEmail, Validators.compose([
                Validators.required,
                Validators.email,
            ]),
            ]
        });
    }

    clear() {
        this.recoverPasswordForm.reset();
    }

    recoverPassword() {

        this.api.post('auth/recover-password', this.recoverPasswordForm.value)
            .subscribe(
                res => {
                    this.emailSend = true;
                    this.successMessage = res.message;
                }
            );
    }

}

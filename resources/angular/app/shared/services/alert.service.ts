import {Injectable} from '@angular/core';
import {Observable} from 'rxjs';
import {Subject} from 'rxjs/Subject';

declare var $: any;

@Injectable()
export class AlertService {
    private subject = new Subject<any>();

    constructor() {
    }

    /**
     * Alert Modal
     *
     * @param {string} type 'confirm' or 'alert'
     * @param {string} title String for modal title
     * @param {string} message Message for modal
     * @param {() => void} yesFn Callback function for Yes
     * @param {() => void} noFn Callback function for No
     * @param {string} modalClass Classs for modal sample: 'teal white-text'
     */
    confirmThis(type: string, title: string, message: string, yesFn: () => void, noFn: () => void, modalClass: string = null) {
        this.setConfirmation(type, title, message, yesFn, noFn, modalClass);
        $('#alert-service-modal').modal('open');
    }

    /**
     * Confirmation Modal
     *
     * @param {string} type
     * @param {string} title
     * @param {string} message
     * @param {() => void} yesFn
     * @param {() => void} noFn
     * @param {string} modalClass
     */
    private setConfirmation(type: string, title: string, message: string, yesFn: () => void, noFn: () => void, modalClass: string = null) {
        const that = this;
        this.subject.next({
            type: type ? type : 'confirm',
            title: title ? title : null,
            text: message ? message : null,
            yesFn:
                function () {
                    that.subject.next(); // this will close the modal
                    yesFn();
                    $('#alert-service-modal').modal('close');
                },
            noFn: function () {
                that.subject.next();
                noFn();
                $('#alert-service-modal').modal('close');
            },
            modalClass: modalClass ? 'modal-content ' + modalClass : 'modal-content',
            modalFooterClass: modalClass ? 'modal-footer ' + modalClass : 'modal-footer',
            modalButtonYesClass: modalClass ? 'waves-effect waves-green btn-flat modal-action white-text modal-close' : 'waves-effect waves-green btn-flat modal-action modal-close',
            modalButtonNoClass: modalClass ? 'waves-effect waves-red btn-flat modal-action white-text modal-close' : 'waves-effect waves-red btn-flat modal-action modal-close',
        });
    }

    /**
     * Get alert message
     *
     * @returns {Observable<any>}
     */
    getMessage(): Observable<any> {
        return this.subject.asObservable();
    }
}

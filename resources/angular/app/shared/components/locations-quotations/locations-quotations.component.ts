import {Component, OnInit, EventEmitter, Input, Output} from '@angular/core';
import {environment} from '../../../../environments/environment';
import {ApiService} from '../../services/api.service';
import {forEach} from '@angular/router/src/utils/collection';

@Component({
    selector: 'renova-locations-quotations',
    templateUrl: './locations-quotations.component.html',
    styleUrls: ['./locations-quotations.component.scss']
})
export class LocationsQuotationsComponent implements OnInit {

    siteURL = `${environment.site_url}`;
    locations: any[];
    idLocation = null;
    locationName = null;
    disabled = false;
    getAll = false;
    errorMessages = {
        location: {
            required: 'Este campo es requerido!'
        }
    };

    @Input() classIn: string;
    @Input() selectedIn: any = null;
    @Output() getData = new EventEmitter();

    constructor(private api: ApiService) {
    }

    @Input() set getAllIn(getAll) {
        this.getAll = getAll;
    }

    @Input() set isDisabled(disabled) {
        this.disabled = disabled;
    }

    ngOnInit() {
        this.locations = [];
        this.getLocationsActives();
    }

    getLocationsActives() {

        this.api.get('administration/locations/actives')
            .subscribe(
                resp => {

                    if (this.getAll) {
                        this.locations = resp.data;
                    } else {
                        let locations: any[];
                        locations = [];
                        for (let i = 0; i < resp.data.length; i++) {
                            for (let j = 0; j < resp.data[i].type_reservables.length; j++) {
                                if (resp.data[i].type_reservables[j].quote_online) {
                                    if (locations.length > 0) {
                                        if (locations[locations.length - 1].id !== resp.data[i].id) {
                                            locations[locations.length] = resp.data[i];
                                        }
                                    } else {
                                        locations[locations.length] = resp.data[i];
                                    }
                                }
                            }
                        }
                        this.locations = locations;
                    }
                }
            );

    }

    /*
    * return value attribute and name attribute
    */

    returnIdLocation(e) {

        this.idLocation = e.target.value;
        const nameLocation = e.target.options[e.target.selectedIndex].getAttribute('name');
        let type_reservables: any;
        for (let i = 0; i < this.locations.length; i++) {
            // tslint:disable-next-line:triple-equals
            if (this.locations[i].id == this.idLocation) {
                type_reservables = this.locations[i].type_reservables;
            }
        }
        this.getData.emit({idLocation: this.idLocation, name: nameLocation, type_reservables: type_reservables});
    }

}

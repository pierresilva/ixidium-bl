import {Injectable} from '@angular/core';
import {ApiService} from './api.service';
import {Observable} from 'rxjs/Observable';

@Injectable()
export class ApiSigasService {

    constructor(private api: ApiService) {
    }

    getDataAffiliate(typeDoc, document, withGroupFamiliar = false): Observable<any> {

        let params;

        params = '/' + typeDoc + '/' + document + '?withGroupFamiliar=' + withGroupFamiliar;

        /*
       *  Datos que se reciben del controlador
       *
       *  @person   => Datos de la persona consultada JSON
       *  @success  => <Boolean> si encuentra o no a la persona
       *  @type     => El tipo de consulta de la persona buscada 'Afiliado' ó 'Beneficiario'
       *  @message  => Mensaje del Servicio
       *  @group    => Array de Objetos del grupo Familiar Completo incluido el afiliado
       */

        return this.api.get('reservations/get-affiliates' + params);

    }
}

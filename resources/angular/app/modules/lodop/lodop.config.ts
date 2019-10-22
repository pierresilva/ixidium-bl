import { Injectable } from '@angular/core';

@Injectable({ providedIn: 'root' })
export class LodopConfig {
  /**
   * Información de registro: Número de registro primario
   */
  license: string;
  /**
   * Información de registro: Número de registro adicional A
   */
  licenseA: string;
  /**
   * Información de registro: Número de registro adicional B
   */
  licenseB?: string;
  /**
   * Información de registro: Nombre de la organización registrada
   */
  companyName?: string;
  /**
   * Dirección URL del script remoto de Lodop, ** Nota ** Asegúrese de usar el atributo `name` para especificar el valor de la variable
   *
   * - http://localhost:18000/CLodopfuncs.js
   * - https://localhost:8443/CLodopfuncs.js
   */
  url?: string;
  /**
   * Nombre de variable Lodop, por defecto: `CLODOP`
   */
  name?: string;
  /**
   * El número de cheques, el valor predeterminado '100', se considera una excepción cuando se excede el cheque,
   * porque Lodop necesita conectarse a WebSocket
   */
  checkMaxCount?: number;
}

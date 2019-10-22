import { Injectable, OnDestroy } from '@angular/core';
import { of, Observable, Subject } from 'rxjs';

import { LodopConfig } from './lodop.config';
import { Lodop, LodopPrintResult, LodopResult } from './lodop.types';
import { LazyService } from '../../shared/services/lazy-service';
import { environment } from '../../../environments/environment';

@Injectable({ providedIn: 'root' })
export class LodopService implements OnDestroy {
  private _cog: LodopConfig;
  private pending = false;
  private _lodop: Lodop = null;
  private _init: Subject<LodopResult> = new Subject<LodopResult>();
  private _events: Subject<LodopPrintResult> = new Subject<LodopPrintResult>();

  constructor(private defCog: LodopConfig, private scriptSrv: LazyService) {
    this.cog = defCog;
  }

  /**
   * Obtener o restablecer la configuración
   *
   * **Nota:** Restablecer invertirá los recursos de script de recarga
   */
  get cog() {
    return this._cog;
  }
  set cog(value: LodopConfig) {
    this._cog = {
      url: environment.lodop_url,
      name: 'CLODOP',
      companyName: '',
      checkMaxCount: 100,
      ...this.defCog,
      ...value,
    };
  }

  /** Notificación de cambio de evento */
  get events(): Observable<LodopPrintResult> {
    return this._events.asObservable();
  }

  private check() {
    if (!this._lodop) throw new Error(`segúrese de llamar primero a lodop para obtener el objeto`);
  }

  private request(): void {
    this.pending = true;

    const url = `${this.cog.url}?name=${this.cog.name}`;
    let checkMaxCount = this.cog.checkMaxCount;
    const onResolve = (status, error?: {}) => {
      this._init.next({
        ok: status === 'ok',
        status,
        error,
        lodop: this._lodop,
      });
    };
    const checkStatus = () => {
      --checkMaxCount;
      if (this._lodop.webskt && this._lodop.webskt.readyState === 1) {
        onResolve('ok');
      } else {
        if (checkMaxCount < 0) {
          onResolve('check-limit');
          return;
        }
        setTimeout(() => checkStatus(), 100);
      }
    };

    this.scriptSrv.loadScript(url).then(res => {
      if (res.status !== 'ok') {
        this.pending = false;
        onResolve('script-load-error', res[0]);
        return;
      }
      this._lodop = window.hasOwnProperty(this.cog.name) && (window[this.cog.name] as Lodop);
      if (this._lodop === null) {
        onResolve('load-variable-name-error', { name: this.cog.name });
        return;
      }
      this._lodop.SET_LICENSES(
        this.cog.companyName,
        this.cog.license,
        this.cog.licenseA,
        this.cog.licenseB,
      );
      checkStatus();
    });
  }

  /** Restablecer objeto lodop */
  reset() {
    this._lodop = null;
    this.pending = false;
    this.request();
  }

  /** Obtener objeto lodop */
  get lodop(): Observable<LodopResult> {
    if (this._lodop) return of({ ok: true, lodop: this._lodop } as LodopResult);
    if (this.pending) return this._init.asObservable();

    this.request();

    return this._init.asObservable();
  }

  /** Obtener una lista de impresoras */
  get printer(): string[] {
    this.check();
    const ret: string[] = [];
    const count = this._lodop.GET_PRINTER_COUNT();
    for (let index = 0; index < count; index++) {
      ret.push(this._lodop.GET_PRINTER_NAME(index));
    }
    return ret;
  }

  /**
   * Código adicional al objeto `lodop`, la clase de cadena admite parámetros dinámicos de `{{key}}`
   *
   * **Nota:** El código se refiere a los datos de cadena generados por el diseño de impresión
   *
   * @param code código
   * @param contextObj objeto de contexto de parámetro dinámico
   * @param parser expresión de análisis personalizada, por defecto：`/LODOP\.([^(]+)\(([^\n]+)\);/i`
   */
  attachCode(code: string, contextObj?: {}, parser?: RegExp): void {
    this.check();
    if (!parser) parser = /LODOP\.([^(]+)\(([^\n]+)\);/i;
    code.split('\n').forEach(line => {
      const res = parser.exec(line.trim());
      if (!res) return;
      const fn = this._lodop[res[1]];
      if (fn) {
        let arr: any[];
        try {
          const fakeFn = new Function(`return [${res[2]}]`);
          arr = fakeFn();
        } catch {}

        if (Array.isArray(arr) && contextObj) {
          for (let i = 0; i < arr.length; i++) {
            if (typeof arr[i] === 'string') {
              arr[i] = arr[i].replace(/{{(.*?)}}/g, (match, key) => contextObj[key.trim()] || '');
            }
          }
        }
        fn.apply(this._lodop, arr);
      }
    });
  }

  /**
   * Abra el diseño de impresión y cierre el código automáticamente después de regresar
   *
   * **Nota:** Escuchar automáticamente el evento `On_Return`, se eliminará después de ejecutarse
   */
  design(): Promise<string> {
    this.check();
    const tid = this._lodop.PRINT_DESIGN();
    return new Promise(resolve => {
      this._lodop.On_Return = (taskID: string, value: boolean | string) => {
        if (tid !== taskID) return;
        this._lodop.On_Return = null;
        resolve('' + value);
      };
    });
  }

  private printBuffer: any[] = [];
  private printDo() {
    const data = this.printBuffer.shift();
    if (!data) return;
    this.attachCode(data.code, data.item, data.parser);
    const tid = this._lodop.PRINT();
    this._lodop.On_Return = (taskID: string, value: boolean | string) => {
      if (tid !== taskID) return;
      this._lodop.On_Return = null;
      this._events.next({
        ok: value === true,
        error: value === true ? null : value,
        ...data,
      });
      this.printDo();
    };
  }

  /**
   * Impresión inmediata, generalmente utilizada para impresión por lotes.
   *
   * @param code código
   * @param contextObj objeto de contexto de parámetro dinámico
   * @param parser  expresión de análisis personalizada, por defecto：`/LODOP\.([^(]+)\(([^\n]+)\);/i`
   */
  print(code: string, contextObj: {} | Array<{}>, parser?: RegExp): void {
    this.check();
    if (contextObj) {
      this.printBuffer.push(
        ...(Array.isArray(contextObj) ? contextObj : [contextObj]).map(item => {
          return { code, parser, item };
        }),
      );
    }
    this.printDo();
  }

  ngOnDestroy(): void {
    this._init.unsubscribe();
    this._events.unsubscribe();
  }
}

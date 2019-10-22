import { Component, OnInit } from '@angular/core';
import { LodopService, Lodop } from '../lodop';
import { environment } from '../../../environments/environment';

@Component({
  selector: 'renova-print',
  templateUrl: './print.component.html',
  styleUrls: ['./print.component.scss']
})
export class PrintComponent implements OnInit {

  lodop: Lodop = null;
  error = false;
  printers: any[] = [];
  papers: string[] = [];
  printing = false;
  cog: any = {
    url: environment.lodop_url,
    name: 'CLODOP',
    printer: '',
    paper: '',
    html: `
      <h1>Title</h1>
      <p>这~！@#￥%……&*（）——sdilfjnvn</p>
      <p>这~！@#￥%……&*（）——sdilfjnvn</p>
      <p>这~！@#￥%……&*（）——sdilfjnvn</p>
      <p>这~！@#￥%……&*（）——sdilfjnvn</p>
      <p>这~！@#￥%……&*（）——sdilfjnvn</p>
    `,
  };

  constructor(
    private lodopService: LodopService,
  ) {
    // Cargar el servicio lodop desde el servidor de impresion
    this.lodopService.lodop.subscribe(({ lodop, ok }) => {
      // no se encuentra el servidor de impresion
      if (!ok) {
        this.error = true;
        console.log('Ocurrio un error en el servicio de impresión!');
        return;
      }
      this.error = false;
      console.log(`Servicio de impresión cargado correctamente`);
      this.lodop = lodop;
      // cargar las impresoras disponibles
      this.printers = this.lodopService.printer;
      // seleccionar la impresora
      this.cog.printer = this.printers[0];
      // cambio la impresora a la por defecto para cargar los tamaños de papel
      this.changePinter(this.printers[0]);
      // seleccionar el tamaño de papel
      this.cog.paper = this.papers[0];
    });
  }

  ngOnInit() {
  }

  reload(options: any = { url: environment.lodop_url }) {
    this.printers = [];
    this.papers = [];
    this.cog.printer = '';
    this.cog.paper = '';

    this.lodopService.cog = Object.assign({}, this.cog, options);
    this.error = false;
    if (options === null) {
      this.lodopService.reset();
    }
  }

  changePinter(name: string) {
    this.papers = this.lodop.GET_PAGESIZES_LIST(name, '\n').split('\n');
  }

  print(isPrivew = false) {
    const LODOP = this.lodop;
    LODOP.PRINT_INITA(10, 20, 810, 610, 'Prueba de impresión remota C-Lodop');
    LODOP.SET_PRINTER_INDEXA(this.cog.printer);
    LODOP.SET_PRINT_PAGESIZE(0, 0, 0, this.cog.paper);
    LODOP.ADD_PRINT_TEXT(
      1,
      1,
      300,
      200,
      'El siguiente resultado es el código fuente de esta página y su efecto de visualización:',
    );
    LODOP.ADD_PRINT_TEXT(20, 10, '90%', '95%', this.cog.html);
    LODOP.SET_PRINT_STYLEA(0, 'ItemType', 4);
    LODOP.NewPageA();
    LODOP.ADD_PRINT_HTM(20, 10, '90%', '95%', this.cog.html);
    if (isPrivew) {
      LODOP.PREVIEW();
    } else {
      LODOP.PRINT();
    }
  }

}

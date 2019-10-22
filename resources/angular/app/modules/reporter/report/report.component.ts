import {AfterViewInit, Component, OnDestroy, OnInit, ViewChild, ViewEncapsulation} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {DataTableDirective} from 'angular-datatables';
import {environment} from '../../../../environments/environment';
import {ActivatedRoute} from '@angular/router';
import {ApiService} from '../../../shared/services/api.service';
import {MzToastService} from '@ngx-materialize';
import {Report} from '../models/report';
import 'rxjs/add/operator/toPromise';
import {Subject} from 'rxjs/Subject';
import {BehaviorSubject} from 'rxjs/BehaviorSubject';
import 'rxjs/Rx';
import saveAs from 'file-saver';
import {ResponseContentType} from '@angular/http';

class DataTablesResponse {
  data: any[];
  draw: number;
  recordsFiltered: number;
  recordsTotal: number;
}

@Component({
  selector: 'renova-report',
  templateUrl: './report.component.html',
  styleUrls: ['./report.component.scss'],
  encapsulation: ViewEncapsulation.None,
})

export class ReportComponent implements OnInit, AfterViewInit, OnDestroy {
  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  dtOptions: any;

  dataTable: any[];
  dtTrigger: Subject<any> = new Subject<any>();
  min: number;
  max: number;
  reportConnection: any;
  reportView: any;
  reportFields: any;
  totals: any[] = [];
  modalFilterArray: any = {};

  reportId: any;
  report: Report;
  columnsSubject: BehaviorSubject<any> = new BehaviorSubject<any>([]);
  columns: any[];
  visibleColumns: any[];

  constructor(private http: HttpClient,
              private route: ActivatedRoute,
              private api: ApiService,
              private toast: MzToastService) {
  }

  ngAfterViewInit(): void {
    const that = this;
    $('#select-columns').change((e: any) => {
      that.visibleColumns = [];
      $('#select-columns :selected').each(function (i, sel) {
        that.visibleColumns.push($(sel).val());
      });
      console.log(that.visibleColumns);
    });
  }

  ngOnInit() {
    const that = this;
    this.visibleColumns = [];
    this.report = new Report();
    this.columnsSubject.subscribe((data) => {
      this.columns = data;
      for (let i = 0; i < data.length; i++) {
        this.visibleColumns.push(data[i].title);
      }
    });
    this.route.params.subscribe(params => {
      this.reportId = +params['reportId'];
      this.reportConnection = params['reportConnection'];
      this.reportView = params['reportView'];
      this.reportFields = JSON.parse(params['reportFields']);
      let cols: any[];
      cols = [];
      for (let i = 0; i < this.reportFields.length; i++) {
        cols.push({
          data: this.reportFields[i].name,
          title: this.reportFields[i].title,
          type: this.reportFields[i].filter,
          searchable: false,
        });
      }

      this.columnsSubject.next(cols);
      this.dtTrigger.next();

      this.http.get(environment.api_url + 'reporter/reports/options/' + this.reportId)
        .toPromise()
        .then((response: any) => {
          this.dtTrigger.next();
          this.getData(response.data);
        })
        .catch(error => {
          console.log(error);
        });

    });

  }

  getData(res) {
    let dtConfig;
    dtConfig = res;
    const that = this;
    // dtConfig['bFilter'] = false;
    dtConfig['initComplete'] = () => {
      setInterval(() => {
        console.log('Loggeando');
      }, 5000);
    };

    dtConfig['columns'] = this.columns;

    dtConfig['ajax']['type'] = 'POST';

    dtConfig['dom'] = 'Bfrtip';

    dtConfig['ajax']['data'] = function (d) {
      d['report_columns'] = JSON.stringify(that.reportFields);
    };

    dtConfig['ajax']['dataSrc'] = (json) => {
      that.totals = json['totals'];
      return json['data'];
    };

    dtConfig['fixedColumns'] = true;

    dtConfig['buttons'] = [
      // 'colvis',
      {
        text: 'Exportar XLS',
        key: '1',
        action: function (e, dt, node, config) {
          let params = [];
          // console.log(dt.ajax.params());
          for (let i = 0; i < that.columns.length; i++) {
            for (let j = 0; j < that.visibleColumns.length; j++) {
              if (that.columns[i]['title'] === that.visibleColumns[j]) {
                params.push(that.columns[i]);
              }
            }
          }

          console.log(that.columns, params, dt.ajax.params().columns);

          that.api.post('reporter/get-csv/' + that.reportConnection + '/' + that.reportView + '/' + that.reportId,
            {
              columns: params,
              datatable: dt.ajax.params()
            })
            .subscribe(res => {
              let url = environment.site_url + res.data + '';
              window.open(url);
            });
        }
      },
      {
        text: 'Exportar PDF',
        key: '1',
        action: function (e, dt, node, config) {
          let params = [];
          // console.log(dt.ajax.params());
          for (let i = 0; i < that.columns.length; i++) {
            for (let j = 0; j < that.visibleColumns.length; j++) {
              if (that.columns[i]['title'] === that.visibleColumns[j]) {
                params.push(that.columns[i]);
              }
            }
          }

          console.log(that.columns, params, dt.ajax.params().columns);

          that.api.post('reporter/get-pdf/' + that.reportConnection + '/' + that.reportView + '/' + that.reportId,
            {
              columns: params,
              datatable: dt.ajax.params()
            })
            .subscribe(res => {
              let url = environment.site_url + res.data + '';
              window.open(url);
            });
        }
      }
    ];

    dtConfig['initComplete'] = function () {

      this.api().columns().every(function (i) {
        let column = this;
        let template;

        if (that.columns[i].type === 'number') {
          template = '<select class="dt-search operator browser-default col s12" data-id="' + i + '" id="operator-' + i + '">\n' +
            '      <option value="" selected>Contiene</option>\n' +
            '      <option value="=">Igual</option>\n' +
            '      <option value="<=">Menor o igual</option>\n' +
            '      <option value=">=">Mayor o igual</option>\n' +
            '      <option value="<>">Diferente</option>\n' +
            '      <option value="<=>">Entre</option>\n' +
            '      <option value="reset">Reestablecer</option>\n' +
            '    </select><br>' +
            '<input type="number" class="dt-search start browser-default col s12" data-id="' + i + '" id="start-' + i + '">' +
            '<input type="number" class="dt-search end browser-default col s12" data-id="' + i + '" id="end-' + i + '" style="display: none;">';
        }
        if (that.columns[i].type === 'date') {
          template = '<select class="dt-search operator browser-default col s12" data-id="' + i + '" id="operator-' + i + '">\n' +
            '      <option value="<=" selected>Menor o igual</option>\n' +
            '      <option value=">=">Mayor o igual</option>\n' +
            '      <option value="<>">Diferente</option>\n' +
            '      <option value="<=>">Entre</option>\n' +
            '      <option value="reset">Reestablecer</option>\n' +
            '    </select><br>' +
            '<input type="date" class="dt-search start browser-default col s12" data-id="' + i + '" id="start-' + i + '">' +
            '<input type="date" class="dt-search end browser-default col s12" data-id="' + i + '" id="end-' + i + '" style="display: none;">';
        }
        if (that.columns[i].type === 'string') {
          template = '<select class="dt-search operator browser-default col s12" data-id="' + i + '" id="operator-' + i + '">\n' +
            '      <option value="" selected>Contiene</option>\n' +
            '      <option value="=">Igual</option>\n' +
            '      <option value="reset">Reestablecer</option>\n' +
            '    </select><br>' +
            '<input type="text" class="dt-search start browser-default col s12" data-id="' + i + '" id="start-' + i + '">' +
            '<input type="date" class="dt-search end browser-default col s12" data-id="' + i + '" id="end-' + i + '" style="display: none;">';
        }

        $(template).appendTo($(column.footer()).empty());

        $('.dt-search').keypress(function (e) {
          if (e.which === 13) {
            // console.log($(this).val());
            // let val = $.fn.dataTable.util.escapeRegex($(this).val().toString());
            let operator = $('#operator-' + i).val();
            let start = $('#start-' + i).val();
            let end = $('#end-' + i).val();
            let searchValue = '';
            if (operator !== '' && start !== '' && end !== '') {
              searchValue = '' + operator + '|' + start + '|' + end;
            } else if (operator !== '' && start !== '' && end === '') {
              searchValue = '' + operator + '|' + start;
            } else if (operator === '' && start !== '' && end === '') {
              searchValue = '' + start;
            } else {
              searchValue = '';
            }
            column.search(searchValue, true, false).draw();
          }
        });
      });

      setTimeout(() => {
        $('[name="datatable_length"]').addClass('browser-default');
        let leftOffset, rightOffset;
        leftOffset = parseInt($('.dataTables_info').css('left'));
        rightOffset = parseInt($('.dataTables_info').css('right'));
        $('#datatable-container').scroll(function () {
          $('.dataTables_info').css({
            'left': leftOffset + $(this).scrollLeft()
          });
          $('.dataTables_paginate').css({
            'right': -$(this).scrollLeft()
          });
          $('.dataTables_length').css({
            'left': leftOffset + $(this).scrollLeft()
          });
          $('.dataTables_filter').css({
            'right': -$(this).scrollLeft()
          });
        });
        $('table.dataTable').css('padding-top', '20px');
        $('table.dataTable tfoot td input').css('margin', '0px');
        $('table.dataTable tfoot th, table.dataTable tfoot td').css('padding', '0px 0px !important');
      }, 200);

      $('select.dt-search').change((e: any) => {
        console.log(e.target.value, e.target.dataset['id']);
        if (e.target.value === '<=>') {
          $('#end-' + e.target.dataset['id']).css('display', 'block');
        } else if (e.target.value === 'reset') {
          $('#end-' + e.target.dataset['id']).val('');
          $('#start-' + e.target.dataset['id']).val('');
          $('#operator-' + e.target.dataset['id']).val('');
          $('#end-' + e.target.dataset['id']).css('display', 'none');
        } else {
          $('#end-' + e.target.dataset['id']).css('display', 'none');
        }
      });
    };

    this.dtOptions = res;
    this.rerender();
  }

  downloadFile(data: any) {
    let parsedResponse: string;
    parsedResponse = data;
    const blob = new Blob([parsedResponse], { type: 'application/vnd.ms-excel' });
    const url = window.URL.createObjectURL(blob);
    window.open(url);
  }

  rerender(): void {
    this.dtElement.dtInstance.then((dtInstance: DataTables.Api) => {
      // Destroy the table first
      dtInstance.destroy();
      // Call the dtTrigger to rerender again
      this.dtTrigger.next();
    });

  }

  ngOnDestroy() {
    // We remove the last function in the global ext search array so we do not add the fn each time the component is drawn
    // /!\ This is not the ideal solution as other components may add other search function in this array, so be careful when
    // handling this global variable
    $.fn['dataTable'].ext.search.pop();
    this.dtTrigger.unsubscribe();
  }

  alert(event, text) {
    console.log(event);
    alert(text);
  }

}

import {Component, OnInit, NgZone} from '@angular/core';
import {ApiService} from '../../shared/services/api.service';
import {CouchdbContactsService} from './services/couchdb-contacts.service';

@Component({
  selector: 'renova-couchdb',
  templateUrl: './couchdb.component.html',
  styleUrls: ['./couchdb.component.scss'],
  providers: [
    CouchdbContactsService,
  ]
})

export class CouchdbComponent implements OnInit {
  message: any;
  contacts: Array<any>;
  form: any;

  options: any[];

  constructor(
    private api: ApiService,
    private database: CouchdbContactsService,
    private zone: NgZone
  ) {
    this.contacts = [];
    this.form = {
      id: null,
      first_name: null,
      last_name: null,
    };
  }

  ngOnInit() {
    this.options = [
      {
        text: 'Uno',
        value: 1,
      },
      {
        text: 'Dos',
        value: 2,
      },
      {
        text: 'Tres',
        value: 3,
      },
      {
        text: 'Cuatro',
        value: 4,
      },
      {
        text: 'Cinco',
        value: 5,
      },
      {
        text: 'Seis',
        value: 6,
      },
      {
        text: 'Siete',
        value: 7,
      },
    ];
    this.api.get('couchdb')
      .subscribe(
        data => {
          this.message = data.message;
        }
      );

    this.database.sync('http://admin:colombia1@127.0.0.1:5984/laravel-database');

    this.database.getChangeListener().subscribe(data => {
      for (let i = 0; i < data.change.docs.length; i++) {
        this.zone.run(() => {
          if (data.change.docs[i]._id !== '_design/contacts_filter') {
            this.contacts.push(data.change.docs[i]);
          }
        });
      }
    });

    this.database.fetch().then(result => {
      this.contacts = [];
      for (let i = 0; i < result.rows.length; i++) {
        if (result.rows[i]._id !== '_design/contacts_filter') {
          this.contacts.push(result.rows[i].doc);
        }
      }
    }, error => {
      console.error(error);
    });
  }

  public insert() {

    if (this.form.id && this.form.first_name && this.form.last_name) {
      this.database.put(this.form, this.form.id);
      this.form = {
        id: null,
        first_name: null,
        last_name: null,
      };
    }

    if (this.form.id === null && this.form.first_name && this.form.last_name) {
      this.database.post(this.form);
      this.form = {
        id: null,
        first_name: null,
        last_name: null,
      };
    }
  }

}

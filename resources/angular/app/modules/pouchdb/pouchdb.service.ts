import {Injectable, EventEmitter} from '@angular/core';
import PouchDB from 'pouchdb';

import * as moment from 'moment';

@Injectable({
  providedIn: 'root'
})
export class PouchdbService {
    private isInstantiated: boolean;
    private database: any;
    private listener: EventEmitter<any> = new EventEmitter();

    constructor() {
        if (!this.isInstantiated) {
            this.database = new PouchDB('contacts');
            this.isInstantiated = true;
        }
    }

    public fetch() {
        return this.database.allDocs({include_docs: true});
    }

    public get(id: string) {
        return this.database.get(id);
    }

    public post(doc) {
        return this.database.post(doc)
            .then(result => {
                console.log(result);
            }, error => {
                return new Promise((resolve, reject) => {
                    reject(error);
                });
            });
    }

    public put(document: any, id: string = null) {
        if (id) {
            document._id = id;
        }

        let dateTime = new Date();
        document.created_at = moment(dateTime).format('YYYY-MM-DD HH:mm:ss');
        document.updated_at = moment(dateTime).format('YYYY-MM-DD HH:mm:ss');
        document.deleted_at = null;
        document.type = 'contacts_collection';
        return this.get(id).then(result => {
            document._rev = result._rev;
            return this.database.put(document);
        }, error => {
            if (error.status === '404') {
                return this.database.put(document);
            } else {
                return new Promise((resolve, reject) => {
                    reject(error);
                });
            }
        });
    }

    public sync(remote: string) {
        let remoteDatabase = new PouchDB(remote);
        this.database.sync(remoteDatabase, {
            live: true,
            retry: true,
            continuous: true,
            filter: (doc: any) => {
              if (doc.type === 'contacts_collection') {
                return true;
              }
              return false;
            },
            //query_params: {'type': 'contacts_collection'},
        })
            .on('change', change => {
                this.listener.emit(change);
            })
            .on('error', error => {
                console.error(JSON.stringify(error));
            });
    }

    public getChangeListener() {
        return this.listener;
    }

}


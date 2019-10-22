import {Routes} from '@angular/router';
import {AuthGuard} from './shared/guards/auth.guard';

const auth = 'Autorización';
const Couchdb = 'Couchdb';
const Pouchdb = 'Pouchdb';
const Pages = 'Pages';
const Audit = 'Auditoria';
const Reporter = 'Reporteador';
const Print = 'Imprimir';
const Command = 'Comandos';

// tslint:disable:max-line-length
export const ROUTES: Routes = [
  {
    path: '',
    redirectTo: 'home',
    pathMatch: 'full'
  },
  // home route
  {
    path: 'home',
    loadChildren: './modules/home/home.module#HomeModule'
  },
  // filemanager route
  {
    path: 'filemanager',
    loadChildren: './modules/filemanager/filemanager.module#FilemanagerModule'
  },
  // Auth Section
  {
    path: 'auth',
    loadChildren: './modules/auth/auth.module#AuthModule',
    data: {
      icon: 'account_circle',
      text: 'Autenticación',
      section: auth,
      display: true
    }
  },
  // generated Parameters module routes here //

  // Couchdb Section
  {
    path: 'couchdb',
    loadChildren: './modules/couchdb/couchdb.module#CouchdbModule',
    data: {
      icon: 'screen',
      text: 'Couchdb',
      section: Couchdb,
      display: true,
    },
  },
  // generated CouchDB module routes here //

  // Pouchdb Section
  {
    path: 'pouchdb',
    loadChildren: './modules/pouchdb/pouchdb.module#PouchdbModule',
    data: {
      icon: 'screen',
      text: 'Pouchdb',
      section: Pouchdb,
      display: true,
    },
  },
  // generated PouchDB module routes here //

  // Pages section
  {
    path: 'pages',
    loadChildren: './modules/pages/pages.module#PagesModule',
    data: {
      icon: 'screen',
      text: 'Pages',
      section: Pages,
      display: true,
    },
  },
  {
    path: 'pages/categories',
    loadChildren: './modules/pages/categories/categories.module#CategoriesModule',
    data: {
      icon: 'screen',
      text: 'Categorías',
      section: Pages,
      display: true,
    },
  },
  // generated Pages module routes here //

  // Audit section
  {
    path: 'audit',
    loadChildren: './modules/audit/audit.module#AuditModule',
    data: {
      icon: 'screen',
      text: 'Auditoria',
      section: Audit,
      display: true,
    },
  },
  // generated Audit module routes here //

  // Reporter section
  {
    path: 'reporter',
    loadChildren: './modules/reporter/reporter.module#ReporterModule',
    data: {
      icon: 'screen',
      text: 'Reporteador',
      section: Reporter,
      display: true,
    },
  },
  // generated Reporter module routes here //

  {
    path: 'print',
    loadChildren: './modules/print/print.module#PrintModule',
    data: {
      icon: 'screen',
      text: 'Print',
      section: Print,
      display: true
    },
  },

  {
    path: 'command',
    loadChildren: './modules/command/command.module#CommandModule',
    data: {
      icon: 'screen',
      text: 'Command',
      section: Command,
      display: true,
      title: 'Command Interface'
    },
  },

  // redirect to home when route does not exists (must be last route)
  {
    path: '**',
    redirectTo: 'home'
  },
];

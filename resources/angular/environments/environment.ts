// The file contents for the current environment will overwrite these during build.
// The build system defaults to the dev environment which uses `environment.ts`, but if you do
// `ng build --env=prod` then `environment.prod.ts` will be used instead.
// The list of which env maps to which file can be found in `.angular-cli.json`.

export const environment = {
  production: false,
  api_url: 'http://baseline.test/api/',
  number_company: '00008',
  site_url: 'http://baseline.test/',
  messagesFile: 'assets/messages/es.json',
  couchdb_url: 'http://127.0.0.1:5984/',
  security_api: 'http://10.10.1.42/SeguridadTransversal/public/api/',
  lodop_url: 'https://localhost:8443/CLodopfuncs.js',
  printer_name: 'PRINTPOS',
  preview_print: true,
};

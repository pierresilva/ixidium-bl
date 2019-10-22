<?php

return [
      'source' => [
            'root'            => 'resources/angular/app',
            'page'            => 'pages',
            'components'      => 'components',
            'directives'      => 'directives',
            'config'          => 'config',
            'dialogs'         => 'dialogs',
            'filters'         => 'filters',
            'services'        => 'services',
            'modules'         => 'modules',
      ],
      'suffix' => [
            'component'       => '.component.ts',
            'componentView'   => '.component.html',
            'componentStyle'  => '.component.scss',
            'dialog'          => '.dialog.ts',
            'dialogView'      => '.dialog.html',
            'directive'       => '.directive.ts',
            'service'         => '.service.ts',
            'config'          => '.config.ts',
            'filter'          => '.filter.ts',
            'pageView'        => '.page.html',
            'stylesheet'      => 'scss', // less, scss or css
            'module'          => '.module.ts'
      ],
      'tests' => [
            'enable' => [
                'components'      => true,
                'services'        => true,
                'directives'      => true,
                'modules'         => true,
            ],
            'source' => [
                'root'            => 'e2e/',
                'components'      => 'components',
                'directives'      => 'directives',
                'services'        => 'services',
                'modules'         => 'modules',
            ],
      ],
      'misc' => [
            'auto_import'         => true,
      ],
];

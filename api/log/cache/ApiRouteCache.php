<?php return array (
  0 => 
  array (
    'POST' => 
    array (
      '/alfa-projectlearning.id/api/login' => 'route0',
      '/alfa-projectlearning.id/api/register' => 'route6',
      '/alfa-projectlearning.id/api/export' => 'route8',
      '/alfa-projectlearning.id/api/upload' => 'route9',
      '/alfa-projectlearning.id/api/jupload' => 'route10',
      '/alfa-projectlearning.id/api/lookup' => 'route12',
      '/alfa-projectlearning.id/api/' => 'route19',
    ),
    'OPTIONS' => 
    array (
      '/alfa-projectlearning.id/api/login' => 'route0',
      '/alfa-projectlearning.id/api/register' => 'route6',
      '/alfa-projectlearning.id/api/export' => 'route8',
      '/alfa-projectlearning.id/api/upload' => 'route9',
      '/alfa-projectlearning.id/api/jupload' => 'route10',
      '/alfa-projectlearning.id/api/session' => 'route11',
      '/alfa-projectlearning.id/api/lookup' => 'route12',
      '/alfa-projectlearning.id/api/chart' => 'route13',
      '/alfa-projectlearning.id/api/' => 'route19',
    ),
    'GET' => 
    array (
      '/alfa-projectlearning.id/api/export' => 'route8',
      '/alfa-projectlearning.id/api/jupload' => 'route10',
      '/alfa-projectlearning.id/api/session' => 'route11',
      '/alfa-projectlearning.id/api/lookup' => 'route12',
      '/alfa-projectlearning.id/api/chart' => 'route13',
      '/alfa-projectlearning.id/api/metadata' => 'route17',
      '/alfa-projectlearning.id/api/' => 'route19',
    ),
    'PUT' => 
    array (
      '/alfa-projectlearning.id/api/' => 'route19',
    ),
    'PATCH' => 
    array (
      '/alfa-projectlearning.id/api/' => 'route19',
    ),
    'DELETE' => 
    array (
      '/alfa-projectlearning.id/api/' => 'route19',
    ),
  ),
  1 => 
  array (
    'GET' => 
    array (
      0 => 
      array (
        'regex' => '~^(?|/alfa\\-projectlearning\\.id/api/list/([^/]+)|/alfa\\-projectlearning\\.id/api/list/([^/]+)/(.*)|/alfa\\-projectlearning\\.id/api/view/([^/]+)()()|/alfa\\-projectlearning\\.id/api/view/([^/]+)/(.*)()()|/alfa\\-projectlearning\\.id/api/delete/([^/]+)()()()()|/alfa\\-projectlearning\\.id/api/delete/([^/]+)/(.*)()()()()|/alfa\\-projectlearning\\.id/api/file/([^/]+)/([^/]+)()()()()()|/alfa\\-projectlearning\\.id/api/file/([^/]+)/([^/]+)/(.*)()()()()()|/alfa\\-projectlearning\\.id/api/export/([^/]+)()()()()()()()()|/alfa\\-projectlearning\\.id/api/export/([^/]+)/([^/]+)()()()()()()()())$~',
        'routeMap' => 
        array (
          2 => 
          array (
            0 => 'route1',
            1 => 
            array (
              'table' => 'table',
            ),
          ),
          3 => 
          array (
            0 => 'route1',
            1 => 
            array (
              'table' => 'table',
              'params' => 'params',
            ),
          ),
          4 => 
          array (
            0 => 'route2',
            1 => 
            array (
              'table' => 'table',
            ),
          ),
          5 => 
          array (
            0 => 'route2',
            1 => 
            array (
              'table' => 'table',
              'params' => 'params',
            ),
          ),
          6 => 
          array (
            0 => 'route5',
            1 => 
            array (
              'table' => 'table',
            ),
          ),
          7 => 
          array (
            0 => 'route5',
            1 => 
            array (
              'table' => 'table',
              'params' => 'params',
            ),
          ),
          8 => 
          array (
            0 => 'route7',
            1 => 
            array (
              'table' => 'table',
              'param' => 'param',
            ),
          ),
          9 => 
          array (
            0 => 'route7',
            1 => 
            array (
              'table' => 'table',
              'param' => 'param',
              'key' => 'key',
            ),
          ),
          10 => 
          array (
            0 => 'route8',
            1 => 
            array (
              'param' => 'param',
            ),
          ),
          11 => 
          array (
            0 => 'route8',
            1 => 
            array (
              'param' => 'param',
              'table' => 'table',
            ),
          ),
        ),
      ),
      1 => 
      array (
        'regex' => '~^(?|/alfa\\-projectlearning\\.id/api/export/([^/]+)/([^/]+)/(.*)|/alfa\\-projectlearning\\.id/api/lookup/(.*)()()()|/alfa\\-projectlearning\\.id/api/chart/(.*)()()()()|/alfa\\-projectlearning\\.id/api/permissions/([^/]+)()()()()()|/alfa\\-projectlearning\\.id/api/push/([^/]+)()()()()()()|/alfa\\-projectlearning\\.id/api/twofa/([^/]+)()()()()()()()|/alfa\\-projectlearning\\.id/api/twofa/([^/]+)/([^/]+)()()()()()()()|/alfa\\-projectlearning\\.id/api/chat/([01])()()()()()()()()()|/alfa\\-projectlearning\\.id/api/(.*)()()()()()()()()()())$~',
        'routeMap' => 
        array (
          4 => 
          array (
            0 => 'route8',
            1 => 
            array (
              'param' => 'param',
              'table' => 'table',
              'key' => 'key',
            ),
          ),
          5 => 
          array (
            0 => 'route12',
            1 => 
            array (
              'params' => 'params',
            ),
          ),
          6 => 
          array (
            0 => 'route13',
            1 => 
            array (
              'params' => 'params',
            ),
          ),
          7 => 
          array (
            0 => 'route14',
            1 => 
            array (
              'level' => 'level',
            ),
          ),
          8 => 
          array (
            0 => 'route15',
            1 => 
            array (
              'action' => 'action',
            ),
          ),
          9 => 
          array (
            0 => 'route16',
            1 => 
            array (
              'action' => 'action',
            ),
          ),
          10 => 
          array (
            0 => 'route16',
            1 => 
            array (
              'action' => 'action',
              'parm' => 'parm',
            ),
          ),
          11 => 
          array (
            0 => 'route18',
            1 => 
            array (
              'value' => 'value',
            ),
          ),
          12 => 
          array (
            0 => 'route19',
            1 => 
            array (
              'params' => 'params',
            ),
          ),
        ),
      ),
    ),
    'OPTIONS' => 
    array (
      0 => 
      array (
        'regex' => '~^(?|/alfa\\-projectlearning\\.id/api/list/([^/]+)|/alfa\\-projectlearning\\.id/api/list/([^/]+)/(.*)|/alfa\\-projectlearning\\.id/api/view/([^/]+)()()|/alfa\\-projectlearning\\.id/api/view/([^/]+)/(.*)()()|/alfa\\-projectlearning\\.id/api/add/([^/]+)()()()()|/alfa\\-projectlearning\\.id/api/add/([^/]+)/(.*)()()()()|/alfa\\-projectlearning\\.id/api/edit/([^/]+)()()()()()()|/alfa\\-projectlearning\\.id/api/edit/([^/]+)/(.*)()()()()()()|/alfa\\-projectlearning\\.id/api/delete/([^/]+)()()()()()()()()|/alfa\\-projectlearning\\.id/api/delete/([^/]+)/(.*)()()()()()()()()|/alfa\\-projectlearning\\.id/api/file/([^/]+)/([^/]+)()()()()()()()()())$~',
        'routeMap' => 
        array (
          2 => 
          array (
            0 => 'route1',
            1 => 
            array (
              'table' => 'table',
            ),
          ),
          3 => 
          array (
            0 => 'route1',
            1 => 
            array (
              'table' => 'table',
              'params' => 'params',
            ),
          ),
          4 => 
          array (
            0 => 'route2',
            1 => 
            array (
              'table' => 'table',
            ),
          ),
          5 => 
          array (
            0 => 'route2',
            1 => 
            array (
              'table' => 'table',
              'params' => 'params',
            ),
          ),
          6 => 
          array (
            0 => 'route3',
            1 => 
            array (
              'table' => 'table',
            ),
          ),
          7 => 
          array (
            0 => 'route3',
            1 => 
            array (
              'table' => 'table',
              'params' => 'params',
            ),
          ),
          8 => 
          array (
            0 => 'route4',
            1 => 
            array (
              'table' => 'table',
            ),
          ),
          9 => 
          array (
            0 => 'route4',
            1 => 
            array (
              'table' => 'table',
              'params' => 'params',
            ),
          ),
          10 => 
          array (
            0 => 'route5',
            1 => 
            array (
              'table' => 'table',
            ),
          ),
          11 => 
          array (
            0 => 'route5',
            1 => 
            array (
              'table' => 'table',
              'params' => 'params',
            ),
          ),
          12 => 
          array (
            0 => 'route7',
            1 => 
            array (
              'table' => 'table',
              'param' => 'param',
            ),
          ),
        ),
      ),
      1 => 
      array (
        'regex' => '~^(?|/alfa\\-projectlearning\\.id/api/file/([^/]+)/([^/]+)/(.*)|/alfa\\-projectlearning\\.id/api/export/([^/]+)()()()|/alfa\\-projectlearning\\.id/api/export/([^/]+)/([^/]+)()()()|/alfa\\-projectlearning\\.id/api/export/([^/]+)/([^/]+)/(.*)()()()|/alfa\\-projectlearning\\.id/api/lookup/(.*)()()()()()()|/alfa\\-projectlearning\\.id/api/chart/(.*)()()()()()()()|/alfa\\-projectlearning\\.id/api/permissions/([^/]+)()()()()()()()()|/alfa\\-projectlearning\\.id/api/push/([^/]+)()()()()()()()()()|/alfa\\-projectlearning\\.id/api/twofa/([^/]+)()()()()()()()()()()|/alfa\\-projectlearning\\.id/api/twofa/([^/]+)/([^/]+)()()()()()()()()()()|/alfa\\-projectlearning\\.id/api/(.*)()()()()()()()()()()()())$~',
        'routeMap' => 
        array (
          4 => 
          array (
            0 => 'route7',
            1 => 
            array (
              'table' => 'table',
              'param' => 'param',
              'key' => 'key',
            ),
          ),
          5 => 
          array (
            0 => 'route8',
            1 => 
            array (
              'param' => 'param',
            ),
          ),
          6 => 
          array (
            0 => 'route8',
            1 => 
            array (
              'param' => 'param',
              'table' => 'table',
            ),
          ),
          7 => 
          array (
            0 => 'route8',
            1 => 
            array (
              'param' => 'param',
              'table' => 'table',
              'key' => 'key',
            ),
          ),
          8 => 
          array (
            0 => 'route12',
            1 => 
            array (
              'params' => 'params',
            ),
          ),
          9 => 
          array (
            0 => 'route13',
            1 => 
            array (
              'params' => 'params',
            ),
          ),
          10 => 
          array (
            0 => 'route14',
            1 => 
            array (
              'level' => 'level',
            ),
          ),
          11 => 
          array (
            0 => 'route15',
            1 => 
            array (
              'action' => 'action',
            ),
          ),
          12 => 
          array (
            0 => 'route16',
            1 => 
            array (
              'action' => 'action',
            ),
          ),
          13 => 
          array (
            0 => 'route16',
            1 => 
            array (
              'action' => 'action',
              'parm' => 'parm',
            ),
          ),
          14 => 
          array (
            0 => 'route19',
            1 => 
            array (
              'params' => 'params',
            ),
          ),
        ),
      ),
    ),
    'POST' => 
    array (
      0 => 
      array (
        'regex' => '~^(?|/alfa\\-projectlearning\\.id/api/add/([^/]+)|/alfa\\-projectlearning\\.id/api/add/([^/]+)/(.*)|/alfa\\-projectlearning\\.id/api/edit/([^/]+)()()|/alfa\\-projectlearning\\.id/api/edit/([^/]+)/(.*)()()|/alfa\\-projectlearning\\.id/api/delete/([^/]+)()()()()|/alfa\\-projectlearning\\.id/api/delete/([^/]+)/(.*)()()()()|/alfa\\-projectlearning\\.id/api/export/([^/]+)()()()()()()|/alfa\\-projectlearning\\.id/api/export/([^/]+)/([^/]+)()()()()()())$~',
        'routeMap' => 
        array (
          2 => 
          array (
            0 => 'route3',
            1 => 
            array (
              'table' => 'table',
            ),
          ),
          3 => 
          array (
            0 => 'route3',
            1 => 
            array (
              'table' => 'table',
              'params' => 'params',
            ),
          ),
          4 => 
          array (
            0 => 'route4',
            1 => 
            array (
              'table' => 'table',
            ),
          ),
          5 => 
          array (
            0 => 'route4',
            1 => 
            array (
              'table' => 'table',
              'params' => 'params',
            ),
          ),
          6 => 
          array (
            0 => 'route5',
            1 => 
            array (
              'table' => 'table',
            ),
          ),
          7 => 
          array (
            0 => 'route5',
            1 => 
            array (
              'table' => 'table',
              'params' => 'params',
            ),
          ),
          8 => 
          array (
            0 => 'route8',
            1 => 
            array (
              'param' => 'param',
            ),
          ),
          9 => 
          array (
            0 => 'route8',
            1 => 
            array (
              'param' => 'param',
              'table' => 'table',
            ),
          ),
        ),
      ),
      1 => 
      array (
        'regex' => '~^(?|/alfa\\-projectlearning\\.id/api/export/([^/]+)/([^/]+)/(.*)|/alfa\\-projectlearning\\.id/api/lookup/(.*)()()()|/alfa\\-projectlearning\\.id/api/permissions/([^/]+)()()()()|/alfa\\-projectlearning\\.id/api/push/([^/]+)()()()()()|/alfa\\-projectlearning\\.id/api/twofa/([^/]+)()()()()()()|/alfa\\-projectlearning\\.id/api/twofa/([^/]+)/([^/]+)()()()()()()|/alfa\\-projectlearning\\.id/api/(.*)()()()()()()()())$~',
        'routeMap' => 
        array (
          4 => 
          array (
            0 => 'route8',
            1 => 
            array (
              'param' => 'param',
              'table' => 'table',
              'key' => 'key',
            ),
          ),
          5 => 
          array (
            0 => 'route12',
            1 => 
            array (
              'params' => 'params',
            ),
          ),
          6 => 
          array (
            0 => 'route14',
            1 => 
            array (
              'level' => 'level',
            ),
          ),
          7 => 
          array (
            0 => 'route15',
            1 => 
            array (
              'action' => 'action',
            ),
          ),
          8 => 
          array (
            0 => 'route16',
            1 => 
            array (
              'action' => 'action',
            ),
          ),
          9 => 
          array (
            0 => 'route16',
            1 => 
            array (
              'action' => 'action',
              'parm' => 'parm',
            ),
          ),
          10 => 
          array (
            0 => 'route19',
            1 => 
            array (
              'params' => 'params',
            ),
          ),
        ),
      ),
    ),
    'DELETE' => 
    array (
      0 => 
      array (
        'regex' => '~^(?|/alfa\\-projectlearning\\.id/api/delete/([^/]+)|/alfa\\-projectlearning\\.id/api/delete/([^/]+)/(.*)|/alfa\\-projectlearning\\.id/api/(.*)()())$~',
        'routeMap' => 
        array (
          2 => 
          array (
            0 => 'route5',
            1 => 
            array (
              'table' => 'table',
            ),
          ),
          3 => 
          array (
            0 => 'route5',
            1 => 
            array (
              'table' => 'table',
              'params' => 'params',
            ),
          ),
          4 => 
          array (
            0 => 'route19',
            1 => 
            array (
              'params' => 'params',
            ),
          ),
        ),
      ),
    ),
    'PUT' => 
    array (
      0 => 
      array (
        'regex' => '~^(?|/alfa\\-projectlearning\\.id/api/(.*))$~',
        'routeMap' => 
        array (
          2 => 
          array (
            0 => 'route19',
            1 => 
            array (
              'params' => 'params',
            ),
          ),
        ),
      ),
    ),
    'PATCH' => 
    array (
      0 => 
      array (
        'regex' => '~^(?|/alfa\\-projectlearning\\.id/api/(.*))$~',
        'routeMap' => 
        array (
          2 => 
          array (
            0 => 'route19',
            1 => 
            array (
              'params' => 'params',
            ),
          ),
        ),
      ),
    ),
  ),
);
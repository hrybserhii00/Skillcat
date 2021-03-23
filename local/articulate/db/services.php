<?php

// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Claro web service local plugin.
 *
 * @package    articulate_service
 * @copyright  2021 JKD
 */

$functions = array(
        'local_articulate_save_grade' => array(
                'classname'   => 'local_articulate_external',
                'methodname'  => 'save_grade',
                'classpath'   => 'local/articulate/externallib.php',
                'description' => 'Save grade for Articulate quiz/presentation.',
                'type'        => 'write'
        ),
        'local_articulate_create_users' => array(
                'classname'   => 'local_articulate_external',
                'methodname'  => 'create_users',
                'classpath'   => 'local/articulate/externallib.php',
                'description' => 'Create new user for Articulate quiz/presentation.',
                'type'        => 'write'
        ),
        'local_articulate_enrol_user' => array(
                'classname'   => 'local_articulate_external',
                'methodname'  => 'enrol_user',
                'classpath'   => 'local/articulate/externallib.php',
                'description' => 'Enrol user in course for Articulate quiz/presentation.',
                'type'        => 'write'
        ),
        'local_articulate_send_analytic' => array(
                'classname'   => 'local_articulate_external',
                'methodname'  => 'save_analytic',
                'classpath'   => 'local/articulate/externallib.php',
                'description' => 'Save analytic data for Articulate quiz/presentation.',
                'type'        => 'write'
        )
);


// We define the services to install as pre-build services. A pre-build service is not editable by administrator.
$services = array(
        'Articulate service' => array(
                'functions' => array ('local_articulate_save_grade','local_articulate_create_users','local_articulate_enrol_user','local_articulate_send_analytic'),
                'restrictedusers' => 1,
                'enabled' => 1,
                'shortname' => 'ARTICULATE_SERVICE',
                'downloadfiles' => 0,
                'uploadfiles' => 0
        )
);


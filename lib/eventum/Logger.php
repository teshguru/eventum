<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 encoding=utf-8: */
// +----------------------------------------------------------------------+
// | Eventum - Issue Tracking System                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2015 Eventum Team.                                     |
// |                                                                      |
// | This program is free software; you can redistribute it and/or modify |
// | it under the terms of the GNU General Public License as published by |
// | the Free Software Foundation; either version 2 of the License, or    |
// | (at your option) any later version.                                  |
// |                                                                      |
// | This program is distributed in the hope that it will be useful,      |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of       |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        |
// | GNU General Public License for more details.                         |
// |                                                                      |
// | You should have received a copy of the GNU General Public License    |
// | along with this program; if not, write to:                           |
// |                                                                      |
// | Free Software Foundation, Inc.                                       |
// | 51 Franklin Street, Suite 330                                        |
// | Boston, MA 02110-1301, USA.                                          |
// +----------------------------------------------------------------------+

use Monolog\Handler\StreamHandler;

/**
 * @method static Monolog\Logger app() Application log channel
 * @method static Monolog\Logger db() Database log channel
 */
class Logger extends Monolog\Registry
{
    /**
     * Configure logging for Eventum application.
     *
     * This can be used like:
     *
     * Logger::api()->addError('Sent to $api Logger instance');
     * Logger::application()->addError('Sent to $application Logger instance');
     */
    public static function initialize()
    {
        // create 'app' instance, it will be used base of other loggers
        $path = APP_LOG_PATH . '/eventum.log';
        $logfile = new StreamHandler($path, Monolog\Logger::WARNING);

        static::createLogger('app', array(), array())->pushHandler($logfile);

        // add logger for database
        static::createLogger('db');
    }

    /**
     * Helper to create named logger and add it to registry.
     * If handlers or processors not specified, they are taken from 'app' logger.
     *
     * This could be useful, say in LDAP Auth Adapter:
     *
     * $logger = Logger::createLogger('ldap');
     * $logger->error('ldap error')
     *
     * @param string $name
     * @param array $handlers
     * @param array $processors
     * @return \Monolog\Logger
     */
    public static function createLogger($name, $handlers = null, $processors = null)
    {
        if ($handlers === null) {
            $handlers = self::getInstance('app')->getHandlers();
        }
        if ($processors === null) {
            $processors = self::getInstance('app')->getProcessors();
        }

        $logger = new Monolog\Logger($name, $handlers, $processors);

        Monolog\Registry::addLogger($logger);

        return $logger;
    }
}

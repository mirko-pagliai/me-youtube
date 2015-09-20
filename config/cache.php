<?php
/**
 * This file is part of MeYoutube.
 *
 * MeYoutube is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * MeYoutube is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with MeYoutube.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author		Mirko Pagliai <mirko.pagliai@gmail.com>
 * @copyright	Copyright (c) 2015, Mirko Pagliai for Nova Atlantis Ltd
 * @license		http://www.gnu.org/licenses/agpl.txt AGPL License
 * @link		http://git.novatlantis.it Nova Atlantis Ltd
 */

define('ME_YOUTUBE_PATH', CACHE.'me_youtube'.DS);

//Default options (with File engine)
$options = [
    'className' => 'File',
	'duration'	=> '+999 days',
	'path'		=> ME_YOUTUBE_PATH,
	'prefix'	=> '',
	'mask'		=> 0777
];

return ['Cache' => [
	/**
	 * Default configuration
	 */
	//MeYoutube default configuration
	'meyoutube'		=> $options,
	//App default configuration
	'default'	=> array_merge($options, ['path' => CACHE]),
	
	'videos'	=> array_merge($options, ['path' => ME_YOUTUBE_PATH.'videos'])
]];
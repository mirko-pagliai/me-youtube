<?php
/**
 * This file is part of me-cms-youtube.
 *
 * me-cms-youtube is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * me-cms-youtube is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with me-cms-youtube.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author      Mirko Pagliai <mirko.pagliai@gmail.com>
 * @copyright   Copyright (c) 2016, Mirko Pagliai for Nova Atlantis Ltd
 * @license     http://www.gnu.org/licenses/agpl.txt AGPL License
 * @link        http://git.novatlantis.it Nova Atlantis Ltd
 */
namespace MeCmsYoutube\Model\Validation;

use MeCms\Model\Validation\AppValidator;

/**
 * Video validator class
 */
class VideoValidator extends AppValidator
{
    /**
     * Construct.
     *
     * Adds some validation rules.
     * @uses MeCms\Model\Validation\AppValidator::__construct()
     */
    public function __construct()
    {
        parent::__construct();

        //User (author)
        $this->requirePresence('user_id', 'create');

        //YouTube ID
        $this->add('youtube_id', [
            'validYoutubeId' => [
                'message' => __d('me_cms_youtube', 'You have to enter a valid {0} ID', 'YouTube'),
                'rule' => function ($value, $context) {
                    return (bool)preg_match('/^[A-z0-9\-_]{11}$/', $value);
                },
            ],
        ])->requirePresence('youtube_id', 'create');

        //Category
        $this->add('category_id', [
            'naturalNumber' => [
                'message' => __d('me_cms', 'You have to select a valid option'),
                'rule' => 'naturalNumber',
            ],
        ])->requirePresence('category_id', 'create');

        //Title
        $this->requirePresence('title', 'create');

        //Text
        $this->requirePresence('text', 'create');

        //"Is spot"
        $this->add('is_spot', [
            'boolean' => [
                'message' => __d('me_cms', 'You have to select a valid option'),
                'rule' => 'boolean',
            ],
        ]);

        //Seconds
        $this->add('seconds', [
            'naturalNumber' => [
                'message' => __d('me_cms', 'You have to enter a valid value'),
                'rule' => 'naturalNumber',
            ],
        ])
        ->requirePresence('seconds', 'create');

        //Duration
        $this->add('duration', [
            'validDuration' => [
                'message' => __d('me_cms', 'You have to enter a valid value'),
                'rule' => function ($value, $context) {
                    return (bool)preg_match('/^(\d{2}:){1,2}\d{2}$/', $value);
                },
            ],
        ])
        ->requirePresence('duration', 'create');
    }
}

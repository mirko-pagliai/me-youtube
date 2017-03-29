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
namespace MeCmsYoutube\Model\Entity;

use Cake\ORM\Entity;

/**
 * YoutubeVideosCategory entity
 * @property int $id
 * @property int $parent_id
 * @property \MeCmsYoutube\Model\Entity\ParentVideosCategory $parent_videos_category
 * @property int $lft
 * @property int $rght
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property int $video_count
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property \MeCmsYoutube\Model\Entity\ChildVideosCategory[] $child_videos_categories
 */
class VideosCategory extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity()
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
        'video_count' => false,
        'modified' => false,
    ];
}

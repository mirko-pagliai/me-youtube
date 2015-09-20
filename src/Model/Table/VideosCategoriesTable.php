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
namespace MeYoutube\Model\Table;

use Cake\Cache\Cache;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use MeCms\Model\Table\AppTable;
use MeYoutube\Model\Entity\VideosCategory;

/**
 * VideosCategories model
 */
class VideosCategoriesTable extends AppTable {
	/**
	 * Called after an entity has been deleted
	 * @param \Cake\Event\Event $event Event object
	 * @param \Cake\ORM\Entity $entity Entity object
	 * @param \ArrayObject $options Options
	 * @uses Cake\Cache\Cache::clear()
	 */
	public function afterDelete(\Cake\Event\Event $event, \Cake\ORM\Entity $entity, \ArrayObject $options) {
		Cache::clear(FALSE, 'videos');		
	}
	
	/**
	 * Called after an entity is saved.
	 * @param \Cake\Event\Event $event Event object
	 * @param \Cake\ORM\Entity $entity Entity object
	 * @param \ArrayObject $options Options
	 * @uses Cake\Cache\Cache::clear()
	 */
	public function afterSave(\Cake\Event\Event $event, \Cake\ORM\Entity $entity, \ArrayObject $options) {
		Cache::clear(FALSE, 'videos');
	}
	
    /**
     * Returns a rules checker object that will be used for validating application integrity
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules) {
        $rules->add($rules->existsIn(['parent_id'], 'Parents'));
        return $rules;
    }
	
	/**
	 * "Active" find method
	 * @param Query $query Query object
	 * @param array $options Options
	 * @return Query Query object
	 */
	public function findActive(Query $query, array $options) {
        $query->where([sprintf('%s.video_count >', $this->alias()) => 0]);
		
        return $query;
    }
	
	/**
	 * Gets the categories list
	 * @return array List
	 */
	public function getList() {
		return $this->find('list')
			->cache('categories_list', 'videos')
			->toArray();
	}
	
	/**
	 * Gets the categories tree list
	 * @return array List
	 */
	public function getTreeList() {
		return $this->find('treeList')
			->cache('categories_tree_list', 'videos')
			->toArray();
	}
	
    /**
     * Initialize method
     * @param array $config The table configuration
     */
    public function initialize(array $config) {
        $this->table('youtube_videos_categories');
        $this->displayField('title');
        $this->primaryKey('id');
        $this->addBehavior('MeCms.Tree');
        $this->belongsTo('Parents', [
            'className' => 'MeYoutube.VideosCategories',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('Childs', [
            'className' => 'MeYoutube.VideosCategories',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('Videos', [
            'className' => 'MeYoutube.Videos',
            'foreignKey' => 'category_id'
        ]);
    }

    /**
     * Default validation rules
     * @param \Cake\Validation\Validator $validator Validator instance
	 * @return \MeYoutube\Model\Validation\VideosCategoryValidator
	 */
    public function validationDefault(\Cake\Validation\Validator $validator) {
		return new \MeYoutube\Model\Validation\VideosCategoryValidator;
    }
}
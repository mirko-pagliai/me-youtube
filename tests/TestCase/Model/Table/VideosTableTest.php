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
namespace MeCmsYoutube\Test\TestCase\Model\Table;

use ArrayObject;
use Cake\Cache\Cache;
use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use MeTools\TestSuite\TestCase;

/**
 * VideosTableTest class
 */
class VideosTableTest extends TestCase
{
    /**
     * @var \MeCmsYoutube\Model\Table\VideosTable
     */
    protected $Videos;

    /**
     * Fixtures
     * @var array
     */
    public $fixtures = [
        'plugin.me_cms.users',
        'plugin.me_cms_youtube.youtube_videos',
        'plugin.me_cms_youtube.youtube_videos_categories',
    ];

    /**
     * Setup the test case, backup the static object values so they can be
     * restored. Specifically backs up the contents of Configure and paths in
     *  App if they have not already been backed up
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->Videos = TableRegistry::get('MeCmsYoutube.Videos');

        Cache::clear(false, $this->Videos->cache);
    }

    /**
     * Test for `cache` property
     * @test
     */
    public function testCacheProperty()
    {
        $this->assertEquals('videos', $this->Videos->cache);
    }

    /**
     * Test for `afterDelete()` method
     * @test
     */
    public function testAfterDelete()
    {
        $this->Videos = $this->getMockForModel($this->Videos->getRegistryAlias(), ['setNextToBePublished']);

        $this->Videos->expects($this->once())
            ->method('setNextToBePublished');

        $this->Videos->afterDelete(new Event(null), new Entity, new ArrayObject);
    }

    /**
     * Test for `afterSave()` method
     * @test
     */
    public function testAfterSave()
    {
        $this->Videos = $this->getMockForModel($this->Videos->getRegistryAlias(), ['setNextToBePublished']);

        $this->Videos->expects($this->once())
            ->method('setNextToBePublished');

        $this->Videos->afterSave(new Event(null), new Entity, new ArrayObject);
    }

    /**
     * Test for `beforeSave()` method
     * @test
     */
    public function testBeforeSave()
    {
        $this->Videos = $this->getMockForModel($this->Videos->getRegistryAlias(), ['getInfo']);

        $this->Videos->method('getInfo')
            ->will($this->returnValue((object)[
                'preview' => 'https://i.ytimg.com/vi/vlSR8Wlmpac/hqdefault.jpg',
                'text' => 'Example test',
                'title' => 'Beethoven - Symphony No. 9 in D minor: Ode to Joy [HD]',
                'seconds' => 778,
                'duration' => '12:58',
            ]));

        $saved = $this->Videos->save($this->Videos->newEntity([
            'youtube_id' => 'vlSR8Wlmpac',
            'user_id' => 1,
            'category_id' => 1,
            'title' => 'Example of title',
            'text' => 'Example of text',
        ]));

        $this->assertEquals(778, $saved->seconds);
        $this->assertEquals('12:58', $saved->duration);
    }

    /**
     * Test for `buildRules()` method
     * @test
     */
    public function testBuildRules()
    {
        $entity = $this->Videos->newEntity([
            'youtube_id' => 'vlSR8Wlmpac',
            'user_id' => 999,
            'category_id' => 999,
            'title' => 'My title',
            'text' => 'My text',
        ]);
        $this->assertFalse($this->Videos->save($entity));
        $this->assertEquals([
            'category_id' => ['_existsIn' => I18N_SELECT_VALID_OPTION],
            'user_id' => ['_existsIn' => I18N_SELECT_VALID_OPTION],
        ], $entity->getErrors());
    }

    /**
     * Test for `initialize()` method
     * @test
     */
    public function testInitialize()
    {
        $this->assertEquals('youtube_videos', $this->Videos->getTable());
        $this->assertEquals('title', $this->Videos->getDisplayField());
        $this->assertEquals('id', $this->Videos->getPrimaryKey());

        $this->assertInstanceOf('Cake\ORM\Association\BelongsTo', $this->Videos->Categories);
        $this->assertEquals('category_id', $this->Videos->Categories->getForeignKey());
        $this->assertEquals('INNER', $this->Videos->Categories->getJoinType());
        $this->assertEquals('MeCmsYoutube.VideosCategories', $this->Videos->Categories->className());
        $this->assertInstanceOf('MeCmsYoutube\Model\Table\VideosCategoriesTable', $this->Videos->Categories->getTarget());
        $this->assertEquals('MeCmsYoutube.VideosCategories', $this->Videos->Categories->getTarget()->getRegistryAlias());
        $this->assertEquals('Categories', $this->Videos->Categories->getAlias());

        $this->assertInstanceOf('Cake\ORM\Association\BelongsTo', $this->Videos->Users);
        $this->assertEquals('user_id', $this->Videos->Users->getForeignKey());
        $this->assertEquals('INNER', $this->Videos->Users->getJoinType());
        $this->assertEquals('MeCms.Users', $this->Videos->Users->className());

        $this->assertTrue($this->Videos->hasBehavior('Timestamp'));
        $this->assertTrue($this->Videos->hasBehavior('CounterCache'));

        $this->assertInstanceOf('MeCmsYoutube\Model\Validation\VideoValidator', $this->Videos->getValidator());
    }

    /**
     * Test for the `belongsTo` association with `VideosCategories`
     * @test
     */
    public function testBelongsToVideosCategories()
    {
        $entity = $this->Videos->findById(3)->contain('Categories')->first();

        $this->assertInstanceOf('MeCmsYoutube\Model\Entity\VideosCategory', $entity->category);
        $this->assertEquals(4, $entity->category->id);
    }

    /**
     * Test for the `belongsTo` association with `Users`
     * @test
     */
    public function testBelongsToUsers()
    {
        $entity = $this->Videos->findById(2)->contain('Users')->first();

        $this->assertInstanceOf('MeCms\Model\Entity\User', $entity->user);
        $this->assertEquals(4, $entity->user->id);
    }

    /**
     * Test for `find()` method
     * @test
     */
    public function testFind()
    {
        $this->Videos->find();

        //Writes `next_to_be_published` and some data on cache
        Cache::write('next_to_be_published', time() - 3600, $this->Videos->cache);
        Cache::write('someData', 'someValue', $this->Videos->cache);

        $this->assertNotEmpty(Cache::read('next_to_be_published', $this->Videos->cache));
        $this->assertNotEmpty(Cache::read('someData', $this->Videos->cache));

        //The cache will now be cleared
        $this->Videos->find();

        $this->assertEmpty(Cache::read('next_to_be_published', $this->Videos->cache));
        $this->assertEmpty(Cache::read('someData', $this->Videos->cache));
    }

    /**
     * Test for `findActive()` method
     * @test
     */
    public function testFindActive()
    {
        $query = $this->Videos->find('active');
        $this->assertStringEndsWith('FROM youtube_videos Videos WHERE (Videos.active = :c0 AND Videos.created <= :c1 AND Videos.is_spot = :c2)', $query->sql());

        $this->assertTrue($query->getValueBinder()->bindings()[':c0']['value']);
        $this->assertInstanceOf('Cake\I18n\Time', $query->getValueBinder()->bindings()[':c1']['value']);
        $this->assertFalse($query->getValueBinder()->bindings()[':c2']['value']);
    }

    /**
     * Test for `findActiveSpot()` method
     * @test
     */
    public function testFindActiveSpot()
    {
        $query = $this->Videos->find('activeSpot');
        $this->assertStringEndsWith('FROM youtube_videos Videos WHERE (Videos.active = :c0 AND Videos.is_spot = :c1 AND Videos.created <= :c2)', $query->sql());

        $this->assertTrue($query->getValueBinder()->bindings()[':c0']['value']);
        $this->assertTrue($query->getValueBinder()->bindings()[':c1']['value']);
        $this->assertInstanceOf('Cake\I18n\Time', $query->getValueBinder()->bindings()[':c2']['value']);
    }

    /**
     * Test for `getRandomSpots()` method
     * @test
     */
    public function testGetRandomSpots()
    {
        $videos = $this->Videos->getRandomSpots();
        $this->assertInstanceOf('MeCmsYoutube\Model\Entity\Video', $videos);
        $this->assertEquals(1, count($videos->toArray()));

        $videosFromCache = Cache::read('all_spots', $this->Videos->cache);
        $this->assertNotEmpty($videosFromCache);
        $this->assertInstanceOf('MeCmsYoutube\Model\Entity\Video', $videosFromCache);

        $videos = $this->Videos->getRandomSpots(2);
        $this->assertInstanceOf('MeCmsYoutube\Model\Entity\Video', $videos);
        $this->assertEquals(2, count($videos->toArray()));

        $this->assertEquals(
            Cache::read('all_spots', $this->Videos->cache)->toArray(),
            $videosFromCache->toArray()
        );
    }

    /**
     * Test for `queryFromFilter()` method
     * @test
     */
    public function testQueryFromFilter()
    {
        $data = ['spot' => true];

        $query = $this->Videos->queryFromFilter($this->Videos->find(), $data);
        $this->assertStringEndsWith('FROM youtube_videos Videos WHERE Videos.is_spot = :c0', $query->sql());

        $this->assertTrue($query->getValueBinder()->bindings()[':c0']['value']);
    }
}

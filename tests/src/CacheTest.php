<?php
namespace Ferrari\Cache;

class CacheTest extends \PHPUnit_Framework_TestCase
{

    private function createMock()
    {

        return $this->getMockBuilder('\Doctrine\Common\Cache\Cache')
            ->setMethods(array('fetch',
                               'contains',
                               'save',
                               'delete',
                               'getStats'))
            ->getMock();
    }

    /**
     * @testdox Add item to the cache will store data and return true
     */
    public function testAddItemToTheCache()
    {

        $mock = $this->createMock();

        $mock->expects($this->once())
             ->method('save')
             ->with($this->equalTo('key'), $this->equalTo(1))
             ->will($this->returnValue(true));

        $cache = new Cache($mock);

        $success = $cache->save('key', 1);

        $this->assertTrue($success);
    }

    /**
     * @testdox We can retrive an item after adding it
     */
    public function testAddAndRetrieveItemToTheCache()
    {

        $mock = $this->createMock();

        $mock->expects($this->once())
             ->method('save')
             ->with($this->equalTo('key'), $this->equalTo(1))
             ->will($this->returnValue(true));

        $mock->expects($this->once())
             ->method('fetch')
             ->with($this->equalTo('key'))
             ->will($this->returnValue(1));

        $cache = new Cache($mock);

        $success = $cache->save('key', 1);

        $this->assertTrue($success);
        $this->assertEquals(1, $cache->fetch('key'));
    }

    /**
     * @testdox Return false if the cache id does not exists
     */
    public function testFetchKeyWithoutData()
    {

        $mock = $this->createMock();

        $mock->expects($this->once())
             ->method('fetch')
             ->with($this->equalTo('key'))
             ->will($this->returnValue(false));

        $cache = new Cache($mock);

        $this->assertFalse($cache->fetch('key'));
    }

    /**
     * @testdox Access the cache as array when item exists
     */
    public function testUseCacheAsArrayWhenIdExists()
    {

        $mock = $this->createMock();

        $mock->expects($this->once())
             ->method('save')
             ->with($this->equalTo('key'), $this->equalTo(1))
             ->will($this->returnValue(true));

        $mock->expects($this->once())
             ->method('fetch')
             ->with($this->equalTo('key'))
             ->will($this->returnValue(1));


        $cache = new Cache($mock);

        $cache['key'] = 1;

        $data = $cache['key'];

        $this->assertEquals(1, $data);
    }

    /**
     * @testdox Access the cache as array when item does not exists
     */
    public function testUseCacheAsArrayWhenIdDoesNotExists()
    {
        $mock = $this->createMock();

        $mock->expects($this->once())
             ->method('fetch')
             ->with($this->equalTo('bla'))
             ->will($this->returnValue(false));

        $cache = new Cache($mock);

        $unexistent = $cache['bla'];

        $this->assertFalse($unexistent);
    }

    /**
     * @testdox Check if the cache contains a value
     */
    public function testCacheContainAnId()
    {

        $mock = $this->createMock();

        $mock->expects($this->any())
             ->method('contains')
             ->with($this->equalTo('key'))
             ->will($this->returnValue(true));

        $cache = new Cache($mock);

        $exists = isset($cache['key']);

        $this->assertTrue($exists);
    }

    /**
     * @testdox Check if the cache dont contain a value
     */
    public function testCacheDontContainAnId()
    {

        $mock = $this->createMock();

        $mock->expects($this->any())
             ->method('contains')
             ->with($this->equalTo('bla'))
             ->will($this->returnValue(false));

        $cache = new Cache($mock);

        $exists = isset($cache['bla']);

        $this->assertFalse($exists);
    }

    /**
     * @testdox Delete item from the cache
     */
    public function testDeleteItemFromCache()
    {

        $mock = $this->createMock();

        $mock->expects($this->once())
             ->method('delete')
             ->with($this->equalTo('key'))
             ->will($this->returnValue(true));

        $cache = new Cache($mock);

        $success = $cache->delete('key');

        $this->assertTrue($success);
    }

    /**
     * @testdox Delete item from the cache as array
     */
    public function testDeleteItemFromCacheAsArray()
    {

        $mock = $this->createMock();

        $mock->expects($this->once())
             ->method('delete')
             ->with($this->equalTo('key'))
             ->will($this->returnValue(true));

        $cache = new Cache($mock);

        unset($cache['key']);
    }

    /**
     * @testdox Get an item from cache or create with a callback
     */
    public function testCacheGetWithCallback()
    {

        $mock = $this->createMock();

        $mock->expects($this->any())
             ->method('fetch')
             ->with($this->equalTo('key'))
             ->will($this->returnValue(false));

        $mock->expects($this->any())
             ->method('save')
             ->with($this->equalTo('key'), $this->equalTo('lorem ipsum'))
             ->will($this->returnValue(true));

        $cache = new Cache($mock);

        $data = $cache->get('key', function () {
            return 'lorem ipsum';
        });

        $this->assertEquals('lorem ipsum', $data);
    }

    /**
     * @testdox Get an unexistent item from cache without callback
     */
    public function testCacheGetUnexistentIdWithoutCallback()
    {

        $mock = $this->createMock();

        $mock->expects($this->any())
             ->method('fetch')
             ->with($this->equalTo('key'))
             ->will($this->returnValue(false));

        $cache = new Cache($mock);

        $data = $cache->get('key');

        $this->assertFalse($data);

    }

    /**
     * @testdox Get an existent item from cache with callback
     */
    public function testCacheGetExistentIdWithCallback()
    {

        $mock = $this->createMock();

        $mock->expects($this->any())
             ->method('contains')
             ->with($this->equalTo('key'))
             ->will($this->returnValue(true));

        $mock->expects($this->any())
             ->method('fetch')
             ->with($this->equalTo('key'))
             ->will($this->returnValue('hello world'));

        $cache = new Cache($mock);

        $data = $cache->get('key', function () {
            return 'lorem ipsum';
        });

        $this->assertEquals('hello world', $data);

    }

    /**
     * @testdox getStats must return an array
     */
    public function testCacheGetStats()
    {

        $mock = $this->createMock();

        $mock->expects($this->any())
             ->method('getStats')
             ->will($this->returnValue(array()));

        $cache = new Cache($mock);

        $stats = $cache->getStats();

        $this->assertTrue(is_array($stats));
    }
}

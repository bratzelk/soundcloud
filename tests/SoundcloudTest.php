<?php

use Njasm\Soundcloud\Resource\Resource;
use Njasm\Soundcloud\Auth\Auth;

Class SoundcloudTest extends \PHPUnit_Framework_TestCase
{
    public $soundcloud;
    
    public function setUp()
    {
        $clientID = "ClientIDHash";
        $clientSecret = "ClientSecretHash";
        $uriCallback = "http://example.com/soundcloud";
        $this->soundcloud = new \Njasm\Soundcloud\Soundcloud($clientID, $clientSecret, $uriCallback);
    }
    
    /**
     * Auth tests.
     */
    public function testGetAuthClientID()
    {
        $this->assertEquals("ClientIDHash", $this->soundcloud->getAuthClientID());        
    }
    
    public function testSetAndGetAuthToken()
    {
        $token = "1-12345-1234567-8cee6a54ad797923";
        $this->soundcloud->setAuthToken($token);
        $this->assertEquals("1-12345-1234567-8cee6a54ad797923", $this->soundcloud->getAuthToken());
    }
    
    public function testNulledGetAuthToken()
    {
        $this->assertNull($this->soundcloud->getAuthToken());
    }
    
    public function testNulledGetAuthScope()
    {
        $this->assertNull($this->soundcloud->getAuthScope());
    }
    
    /**
     * Resources tests.
     */
    public function testGetResourceCreation()
    {
        $property = $this->reflectProperty("Njasm\\Soundcloud\\Soundcloud", "resource");
        $this->soundcloud->get('/resolve');       
        $this->assertTrue($property->getValue($this->soundcloud) instanceof Resource);
        $this->assertEquals("get", $property->getValue($this->soundcloud)->getVerb());        
    }

    public function testPostResourceCreation()
    {
        $property = $this->reflectProperty("Njasm\\Soundcloud\\Soundcloud", "resource");
        $this->soundcloud->post('/resolve');       
        $this->assertTrue($property->getValue($this->soundcloud) instanceof Resource);
        $this->assertEquals("post", $property->getValue($this->soundcloud)->getVerb());        
    }
    
    public function testPutResourceCreation()
    {
        $property = $this->reflectProperty("Njasm\\Soundcloud\\Soundcloud", "resource");
        $this->soundcloud->put('/resolve');       
        $this->assertTrue($property->getValue($this->soundcloud) instanceof Resource);
        $this->assertEquals("put", $property->getValue($this->soundcloud)->getVerb());        
    }
    
    public function testDeleteResourceCreation()
    {
        $property = $this->reflectProperty("Njasm\\Soundcloud\\Soundcloud", "resource");
        $this->soundcloud->delete('/resolve');       
        $this->assertTrue($property->getValue($this->soundcloud) instanceof Resource);
        $this->assertEquals("delete", $property->getValue($this->soundcloud)->getVerb());
    }
    
    public function testSetParams()
    {
        $params = array(
            'url' => 'http://www.soundcloud.com/hybrid-species'
        );
        $property = $this->reflectProperty("Njasm\\Soundcloud\\Soundcloud", "resource");
        $this->soundcloud->get('/resolve');
        $this->soundcloud->setParams($params);
        $this->assertArrayHasKey('url', $property->getValue($this->soundcloud)->getParams());
    }
    
    public function testGetAuthUrl()
    {
        $expected = "https://www.soundcloud.com/connect?client_id=ClientIDHash&scope=non-expiring&display=popup&response_type=token_and_code&redirect_uri=http%3A%2F%2Fexample.com%2Fsoundcloud&state=";
        $this->assertEquals($expected, $this->soundcloud->getAuthUrl());
    }
    
    public function testNoResourceException()
    {
        $this->setExpectedException(
            'Njasm\Soundcloud\Exception\SoundcloudException',
            "No Resource found. you must call a http verb method before Njasm\Soundcloud\Soundcloud::setParams"
        );
        
        $facade = $this->soundcloud->setParams(array('url' => 'http://www.soundcloud.com/hybrid-species'));
    }   
    
    /**
     * Helper method for properties reflection testing.
     */
    private function reflectProperty($class, $property)
    {
        $property = new ReflectionProperty($class, $property);
        $property->setAccessible(true);
        
        return $property;
    }
}

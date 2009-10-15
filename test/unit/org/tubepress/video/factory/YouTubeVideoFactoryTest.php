<?php

require_once dirname(__FILE__) . '/../../../../../../classes/org/tubepress/video/factory/YouTubeVideoFactory.class.php';

class org_tubepress_video_factory_YouTubeVideoFactoryTest extends PHPUnit_Framework_TestCase {
    
    private $_vids;
    
    private $thumbUrls = array("http://img.youtube.com/vi/m3gMgK7h-BA/2.jpg",
					"http://img.youtube.com/vi/m3gMgK7h-BA/1.jpg",
                    "http://img.youtube.com/vi/m3gMgK7h-BA/3.jpg",
                    "http://img.youtube.com/vi/m3gMgK7h-BA/0.jpg");
    
    function setUp() {
        $doc = new DOMDocument();
        $doc->load(dirname(__FILE__) . "/../../../../sample_feed.xml");
        $factory = new org_tubepress_video_factory_YouTubeVideoFactory();
        $this->_vids = $factory->feedToVideoArray($doc, 1);
    }
    
    function testRetrievesAuthorFromDomElement()
    {
        $this->assertEquals($this->_vids[0]->getAuthor(), "dhyrenz");
    }
    
    function testRetrievesCategoryFromDomElement()
    {
        $this->assertEquals($this->_vids[0]->getCategory(), "Music");
    }
    
    function testRetrievesDescriptionFromDomElement()
    {
        $this->assertEquals($this->_vids[0]->getDescription(), "....one of those that will make you say...holy %$#^");
    }
    
    function testRetrievesIdFromDomElement()
    {
        $this->assertEquals($this->_vids[0]->getId(), "m3gMgK7h-BA");
    }
    
    function testRetrievesRatingAverageFromDomElement()
    {
        $this->assertEquals($this->_vids[0]->getRatingAverage(), "4.92");
    }
    
    function testRetrievesRatingCountFromDomElement()
    {
        $this->assertEquals($this->_vids[0]->getRatingCount(), "29,065");
    }
    
    function testRetrievesRuntimeFromDomElement()
    {
        $this->assertEquals($this->_vids[0]->getDuration(), "2:30");
    }

    function testRetrievesKeywordsFromDomElement()
    {
    	$expectedKeywords = array("balboa", "feet", "guitar", "park");
        $this->assertTrue($this->_vids[0]->getKeywords() === $expectedKeywords);
    }
    
    function testRetrievesTitleFromDomElement()
    {
        $this->assertEquals($this->_vids[0]->getTitle(), "amazing guitar player");
    }

    function testRetrievesUploadTimeFromDomElement()
    {
        $this->assertEquals($this->_vids[0]->getTimePublished(), 1161748355);
    }
    
    function testRetrievesUrlFromDomElement()
    {
        $this->assertEquals($this->_vids[0]->getHomeUrl(), "http://www.youtube.com/watch?v=m3gMgK7h-BA");
    }
    
    function testRetrievesViewCountFromDomElement()
    {
        $this->assertEquals($this->_vids[0]->getViewCount(), "5,286,665");
    }
}
?>

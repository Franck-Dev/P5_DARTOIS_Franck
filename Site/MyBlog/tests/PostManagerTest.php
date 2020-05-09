<?php

class PostManagerTest extends \PHPUnit\Framework\TestCase{

    public function testgetPosts(){
        $fixturePost=[1=>['Id'=>'1','title'=>'Test1','description'=>'dyfyufyuyu','author'=>'Franck','createdAt'=> '2020-03-23 16:22:23'],
        2=>['Id'=>'2','title'=>'Test2','description'=>'kqskdl','author'=>'Lememe','createdAt'=> '2020-03-13 16:22:23'],
        3=>['Id'=>'3','title'=>'Test3','description'=>'yudrusil','author'=>'Idem','createdAt'=> '2020-04-23 16:22:23']];
        $this->assertEquals(count($fixturePost),App\src\model\postManager::getPosts($fixturePost));
        //$this->assertClassHasAttribute('title','post');
    }
}
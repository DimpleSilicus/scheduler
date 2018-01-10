<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


class UserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
//        $response=$this->call('GET','/',[],[],[],[
//            'HTTP_Autherization'=>"Basic". base64_encode('dimple.agarwal@silicus.com:dimple'),
//            'PHP_AUTH_USER'=>'dimple.agarwal@silicus.com',
//            'PHP_AUTH_PW'=>'dimple'
//        ]);
        
//        $user= factory("App\\User")->create();
//        var_dump($response);
//        echo "res".$this->assertResponseStatus();
//        var_dump($response->getContent());
        
        $user = new User([
        'id' => 1,
        'name' => 'dimple'
    ]);

    echo "user::".$this->be($user);
        die;
        $this->assertTrue(strpos($response->getContent(),'hello there')!== false);

//        $this->assertTrue(true);
    }
}

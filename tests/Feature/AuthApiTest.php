<?php

namespace Tests\Feature;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthApiTest extends TestCase
{


    public function testregister()
    
    {

        $userData = [
            'uuid'=>Str::uuid(),
            'name' => 'nana',
            'email' => 'nana@example.com',
            'password' => Hash::make(123456789)

        ];
        $response = $this->postJson('/api/register',$userData);
       

        $response->assertStatus(201); 
       
    }


    public function testlogout()
    
      {
        $userData = [
            'email' => 'dina@example.com',
            'password' => '123456789'

        ];
        $response = $this->postJson('/api/login',$userData);
        $token = $response->json('$res')['token'];
        $taskResponse = $this->withHeaders([
          'Authorization' => 'Bearer ' . $token,
         ])->postJson('/api/logout' );

        
         $response->assertStatus(200); 

        }


        public function testlogoutAuth()
      
        {
        
        $taskResponse = $this->postJson('/api/logout' );
       
        $taskResponse ->assertStatus(401);
        $taskResponse ->assertJsonFragment([
            'message' => 'Unauthenticated.',
            ]);

        }





     public function testErrorsForUserRegistration()
     
     {
      
      $response = $this->postJson('/api/register', [
          'name' => '',
          'email' => 'enas', 
          'password' => '',
      ]);
      
      $response->assertStatus(422);
  
       }
  

       public function testErrorsForUserLogin()
       
       {

        $userData = [
            'email' => 'sara@example.com',
            'password' => '123456'

        ];
        
        $response = $this->postJson('/api/login',$userData);
       
        $response->assertStatus(401);
    
         }

    }

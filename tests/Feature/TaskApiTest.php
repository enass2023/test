<?php

namespace Tests\Feature;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Task;
use Tests\TestCase;


class TaskApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
   

      // ........index

      public function testindextask(){


        $userData = [

             'email' => 'dina@example.com',
             
             'password' => '123456789'
        
            ];
            $response = $this->postJson('/api/login',$userData);

            $response->assertStatus(200);
        
            $token = $response->json('$res')['token'];

            $userId = User::where('email', $userData['email'])->first()->id;
            

            $userId = User::where('email', $userData['email'])->first()->id;
         

           $task = Task::factory()->create(['user_id'=>$userId ]);
           
           
         $taskResponse = $this->withHeaders([
                'Authorization' => 'Bearer ' . $token,
              ])->getJson('/api/tasks');
            
            
            $taskResponse->assertStatus(200); 
        
       }



       public function testindexEmptytask(){


        $userData = [

            'email' => 'dina@example.com',
            
            'password' => '123456789'
       
           ];
           $response = $this->postJson('/api/login',$userData);

           $response->assertStatus(200);
       
           $token = $response->json('$res')['token'];
           

           $userId = User::where('email', $userData['email'])->first()->id;
        
          
          
        $taskResponse = $this->withHeaders([
               'Authorization' => 'Bearer ' . $token,
             ])->getJson('/api/tasks');
           
           
           $taskResponse->assertStatus(200);

        
       }




       public function testindexUnauthorizedAccess()
       {
          
           $response = $this->getJson('/api/tasks');
       
           $response->assertStatus(401);
       
         
           $response->assertJsonFragment([
               'message' => 'Unauthenticated.',
           ]);
       }



        // .....indexByid...


        public function testindexByIdtask(){


            $userData = [
    
                 'email' => 'dina@example.com',
                 
                 'password' => '123456789'
            
                ];
                $response = $this->postJson('/api/login',$userData);
            
    
                $response->assertStatus(200);
            
                $token = $response->json('$res')['token'];
    
                $userId = User::where('email', $userData['email'])->first()->id;
             
    
               $task = Task::factory()->create(['user_id'=>$userId ]);
              
               
               $taskResponse = $this->withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                  ])->getJson('/api/tasks/'. $task->id );

                
                
                $taskResponse->assertStatus(200); 
            
           }
    







       


      //    .........Create.........

     public function testcreatetask(){

        $userData = [

            'email' => 'dina@example.com',
            
            'password' => '123456789'
       
           ];
           $response = $this->postJson('/api/login',$userData);

           $response->assertStatus(200);
       
           $token = $response->json('$res')['token'];
          
           $userId = User::where('email', $userData['email'])->first()->id;

    
        $taskResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/tasks',[
            'uuid'=>Str::uuid(),
            'user_id'=>$userId ,
            'title' => 'New Task',
            'description' => 'This is a test task',
            'status' => 'pending',]) ;
        $taskResponse->assertStatus(201); 
       
        
       }




       public function testcreateUnauthorizedAccess()
 
     {
        $userData = [

        'uuid'=>Str::uuid(),
        'user_id'=>1 ,
        'title' => 'New Task',
        'description' => 'This is a test task',
        'status' => 'pending',
   
       ];
   
    $response = $this->postJson('/api/tasks',$userData);

    $response->assertStatus(401);

  
    $response->assertJsonFragment([
        'message' => 'Unauthenticated.',
    ]);
   }


   public function testErrorsForCreateTask()
   {
    
      $userData = [

          'email' => 'dina@example.com',
          
          'password' => '123456789'
     
         ];
         $response = $this->postJson('/api/login',$userData);

         $response->assertStatus(200);
     
         $token = $response->json('$res')['token'];
        
         $userId = User::where('email', $userData['email'])->first()->id;

  
      $taskResponse = $this->withHeaders([
          'Authorization' => 'Bearer ' . $token,
      ])->postJson('/api/tasks',[
          'uuid'=>Str::uuid(),
          'user_id'=>$userId ,
          'title' => '',
          'description' => '',
          'status' => 'pendiassa',]) ;


       $taskResponse->assertStatus(422);

     }




    // .......update......


    public function testupdatetask()
    {

        $userData = [
            'email' => 'dina@example.com',
            'password' => '123456789'
        
         ];

         $data=[

            'title' => 'welcome',
            'description' => 'welcome to laravel project ',
             'status' => 'pending',
 
         ];

        $response = $this->postJson('/api/login',$userData);   
        $response->assertStatus(200);

        $userId = User::where('email', $userData['email'])->first()->id;
       
        $task = Task::factory()->create(['user_id'=>$userId ]);
            
       

        $token = $response->json('$res')['token'];
         $taskResponse = $this->withHeaders([
           'Authorization' => 'Bearer ' . $token,
          ])->putJson('/api/tasks/' . $task->id ,$data);
    
        
        $taskResponse->assertStatus(201); 
    
        
    }


   

     public function testupdateUnauthorizedAccess()
    {
    $data = [

        'title' => 'New Task',
        'description' => 'This is a test task',
        'status' => 'pending',
   
       ];

       $task = Task::factory()->create();
   
      $response = $this->putJson('/api/tasks/' . $task->id ,$data);

       $response->assertStatus(401);  

  
       $response->assertJsonFragment([
        'message' => 'Unauthenticated.',
      ]);
     }


 
       public function testErrorsForUpdateTask()
       {
      
        $userData = [

            'email' => 'dina@example.com',
            
            'password' => '123456789'
       
           ];

           $data=[

            'title' => 'welcome',
            'description' => 'welcome to laravel project ',
             'status' => 'pendingg',
 
         ];

           $response = $this->postJson('/api/login',$userData);

           $response->assertStatus(200);

       
           $token = $response->json('$res')['token'];
          
           $userId = User::where('email', $userData['email'])->first()->id;

           $task = Task::factory()->create(['user_id'=>$userId ]);


           $taskResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
           ])->putJson('/api/tasks/' . $task->id ,$data);
       
          $taskResponse->assertStatus(422);
  
       }


       function testUnauthorizedUserCannotUpdateTask()
       {

        
        $userData = [
            'email' => 'dina@example.com',
            'password' => '123456789'
        
         ];

         $data=[

            'title' => 'welcome',
            'description' => 'welcome to laravel project ',
             'status' => 'pending',
 
         ];

        $response = $this->postJson('/api/login',$userData);   
        $response->assertStatus(200);
       
 
        $task = Task::factory()->create(['user_id'=>3]);
          
        $token = $response->json('$res')['token'];
         $taskResponse = $this->withHeaders([
           'Authorization' => 'Bearer ' . $token,
          ])->putJson('/api/tasks/' . $task->id ,$data);
        
        
        $taskResponse->assertStatus(403); 
    
    

       }




    //......status delete.....




    public function testdeletetask()
    {
        $userData = [
            'email' => 'dina@example.com',
            'password' => '123456789'

        ];
        $response = $this->postJson('/api/login',$userData);

       
        
        $response->assertStatus(200);


        
        $token = $response->json('$res')['token'];

        $userId = User::where('email', $userData['email'])->first()->id;

        $task = Task::factory()->create(['user_id'=>$userId ]);
    
        
        $taskResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson('/api/tasks/' . $task->id);

           
        $taskResponse->assertStatus(200); 

   
         $this->assertSoftDeleted('tasks', [
            'id' => $task->id,
        ]);
  
        
    }


    public function testdeleteUnauthorizedAccess()
    {
    
           $task = Task::factory()->create();
       
        $response = $this->deleteJson('/api/tasks/' . $task->id);
    
        $response->assertStatus(401);
    
      
        $response->assertJsonFragment([
            'message' => 'Unauthenticated.',
        ]);
      }
    
      
      public function testUnauthorizedUserCannotDeleteTask()
      {
          $userData = [
              'email' => 'dina@example.com',
              'password' => '123456789'
          
            ];
    
          $response = $this->postJson('/api/login',$userData);
    
         
          $response->assertStatus(200);
    
    
          $token = $response->json('$res')['token'];
    
         
          $task = Task::factory()->create(['user_id'=>3]);
          
          $taskResponse = $this->withHeaders([
              'Authorization' => 'Bearer ' . $token,
          ])->deleteJson('/api/tasks/' . $task->id);
           
          $taskResponse->assertStatus(403); 
       
    
          
      }





      //......search.......


      public function testSearch()
      {


        $userData=[

          'title' => 'welcome',
          'description' => 'welcome to laravel project ',
          'status' => 'pending',
          'user_id'=>'2'

       ];

      $response = $this->postJson('api/task_search',$userData);  
      $response->assertStatus(200);

      }


      public function testErrorsForSearchTask()
      {
     
        $userData=[

          'title' => 'welcome',
          'description' => 'welcome to laravel project ',
          'status' => 'pend',
          

       ];

      $response = $this->postJson('api/task_search',$userData);
     
      $response->assertStatus(422);
 
      }





    
    



    }




    
   


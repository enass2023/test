<?php

namespace App\Http\Controllers;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Validator;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Http\Traits\GeneralTrait;


class TaskController extends Controller
{

    use GeneralTrait;
   

    public function index()
    {

     $tasks = Task::where('user_id', auth()->id())->get();
       

    if( $tasks->isEmpty())
    {

       return $this->apiResponse("Not found Tasks") ;
    } 

     $tasks=TaskResource::collection($tasks);
     return $this->apiResponse($tasks) ;
   
    }



    public function indexByid($id)
    {
        $task=Task::findOrFail($id);
           if(!$task)
           {
                return $this->apiResponse('not found') ;  

            }
         

           $task=TaskResource::make($task);
           return $this->apiResponse($task); 
       
    }


    public function search(Request $request)
   
    {

        $validate = Validator::make($request->all(),[
            'title' => 'string|max:50|min:1',
            'description' => 'string|max:500|min:2',
            'status' => [ Rule::in(['pending','in progress','completed'])],
            'user_id' => 'integer|exists:users,id'

        ]);
            if($validate->fails()){
            return $this->requiredField($validate->errors()->first());    
         }

       try{ 
          $query = Task::query();
    
          if ($request->has('search')) {
           $query->where('title', 'like', '%' . $request->search . '%')
          ->orWhere('description', 'like', '%' . $request->search . '%');
          }


          if ($request->has('status')) {
          $query->where('status', $request->status);
          }

   
          if ($request->has('user_id')) {
           $query->where('user_id', $request->user_id);
          }

     
         $tasks = $query->paginate(10);
         if( $tasks->isEmpty())
         {

          return $this->apiResponse("Not found in database") ;
         } 
    
         $tasks=TaskResource::collection($tasks);
         return $this->apiResponse($tasks) ;  

         }catch(\Exception $ex){
          return $this->apiResponse($tasks,false,$ex->getMessage(),500);
    
          }
  
    }


   
    public function store(Request $request)
    
    {
        $validate = Validator::make($request->all(),[
            'title' => 'required|string|max:50|min:1',
            'description' => 'required|string|max:500|min:2',
            'status' => ['required', Rule::in(['pending','in progress','completed'])]
            ]);

            if($validate->fails()){
            return $this->requiredField($validate->errors()->first());    
            }
           
            $data=$request->all();
            $data['uuid']=Str::uuid();
            $data['user_id']=Auth::id();
            
            $task=Task::create($data);

            $task=TaskResource::make($task);
            return $this->apiResponse($task,true,null,201);
           
             } 
             

   

   
    
    public function update(Request $request,$id)
    {
       

        $validate = Validator::make($request->all(),[
            'title' => 'string|max:50|min:1',
            'description' => 'string|max:500|min:2',
            'status' => [ Rule::in(['pending','in progress','completed'])]

            ]);
            if($validate->fails()){
            return $this->requiredField($validate->errors()->first());    
            }

            try{

            $task=Task::findOrFail($id);

            if($task->user_id!=Auth::id())
            {

            return $this->Forbidden('not unAuthorized');
            }
            
            $task =$task->update($request->only('title','description','status'));
            
            return $this->apiResponse('succsess update',true,null,201) ; 
         
       

           }catch(\Exception $ex){
            return $this->apiResponse($task,false,$ex->getMessage(),500);
    
           }

        
    }


    public function destroy($id)
    {

        try{
        $task=Task::findOrFail($id);;

      
       if($task->user_id!=Auth::id())
       
       {
        return $this->Forbidden('not unAuthorized');
       }
    
        $task->delete();

        return $this->apiResponse('succsess delete task') ; 
 
    }

    catch(\Exception $ex){
        return $this->apiResponse($task,false,$ex->getMessage(),500);
    
       }
    
}


}
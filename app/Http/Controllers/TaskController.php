<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Task;
use App\Repositories\TaskRepository;

class TaskController extends Controller
{
    protected $tasks;
    
    public function __construct(TaskRepository $tasks){
    	$this->middleware('auth');
    	$this->tasks = $tasks;
    }
    
    public function index(Request $request){
    	return view('tasks.index', [
    		'tasks' => $this->tasks->forUser($request->user()),
    		'success' => true,
    	]);
    }
    
    public function store(Request $request){
    	$this->validate($request, [
    		'name' => 'required|max:255'
    	]);

        //$request->file('photo')->move(public_path('images'), $request->file('photo')->getClientOriginalName());
        $destinationPath = '../../uploads/';
        $request->file('photo')->move($destinationPath, $request->file('photo')->getClientOriginalName());

    	/*$request->user()->tasks()->create([
    		'name'=>$request->name
    	]);
    	return redirect('/tasks');*/
    }
    
    public function destroy(Request $request, Task $task){
    	$this->authorize('destroy', $task);
    	$task->delete();
    	return redirect('/tasks');
    }
}

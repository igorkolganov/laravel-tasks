<?php

namespace App\Http\Controllers;

use App\Http\Resources\Task as TaskResource;
use Illuminate\Http\Request;
use App\Models\Task as ModelTask;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = DB::select('select id from users where authorized = :bol', ['bol' => true]);
        return DB::select('select * from tasks where user_id = :id', ['id' => $userId['0']->id]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userId = DB::select('select id from users where authorized = :bol', ['bol' => true]);

        $task =  new ModelTask();

        $task->user_id = $request->input('user_id', $userId['0']->id);
        $task->title = $request->input('title');
        $task->body = $request->input('body');
        $task->priority = $request->input('priority');
        $task->done = $request->input('done', false);
        $task->changed = $request->input('changed', false);

        DB::insert('insert into tasks (user_id, title, body, priority, done, changed) values (:user_id, :title, :body, :priority, :done, :changed)', ['user_id' => $task->user_id, 'title' => $task->title, 'body' => $task->body, 'priority' => $task->priority, 'done' => $task->done, 'changed' => $task->changed] );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return DB::select('select * from tasks where id = :id', ['id' => $id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $task = new \stdClass();
        $task->title = $request->input('title');
        $task->body = $request->input('body');
        $task->done = $request->input('done');
        $task->priority = $request->input('priority');
        $task->changed = $request->input('changed', true);

        if ($task->done !== null){
            return DB::update('update tasks set done = :done  where id = :id', ['done' => $task->done, 'id' => $id]);
        }else{
            return DB::update('update tasks set title = :title, body = :body, priority = :priority, changed = :changed where id = :id', ['title' => $task->title, 'body' => $task->body, 'priority' => $task->priority, 'changed' => $task->changed, 'id' => $id]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return DB::delete('delete from tasks where id = :id', ['id' => $id]);
    }
}

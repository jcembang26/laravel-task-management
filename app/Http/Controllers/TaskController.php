<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Status;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    /**
     * Get all task data with filters
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $limit = isset($request->limit) ? intval($request->limit) : 15;
        $sort = isset($request->sort) ? $request->sort : 'created_at';
        $dir = isset($request->dir) ? $request->dir : 'desc';
        $keyword = isset($request->keyword) ? (string) $request->keyword : '';
        $filter = isset($request->filter) ? intval($request->filter) : 0;
        $userId = $request->user() ? $request->user()->id : 0;

        $q = Task::select('t1.*')->from('tasks as t1')->with(['status', 'subtasks'])->where('user_id', $userId)->orderBy($sort, $dir);

        if ($filter) {
            $q->where('status_id', $filter);
        }

        if (strlen($keyword) > 0) {
            $q->where('title', 'LIKE', '%'.$keyword.'%');
        }

        $q->addSelect([
            'done_tasks' => function ($query) {
                $query->select(DB::raw('COUNT(id) as count'))
                    ->from('tasks as t2')
                    ->where('t2.status_id', 3)
                    ->whereColumn('t1.id', 't2.parent_id');
            }
        ]);

        $list = $q->paginate($limit);

        $status = Status::orderBy('id', 'ASC')->get();

        $sort = [
            ['value' => 'id', 'sort' => 'asc', 'label' => 'ID - ASC'],
            ['value' => 'id', 'sort' => 'desc', 'label' => 'ID - DESC'],
            ['value' => 'title', 'sort' => 'asc', 'label' => 'Title - ASC'],
            ['value' => 'title', 'sort' => 'desc', 'label' => 'Title - DESC'],
            ['value' => 'created_at', 'sort' => 'asc', 'label' => 'Date - ASC'],
            ['value' => 'created_at', 'sort' => 'desc', 'label' => 'Date - DESC'],
        ];

        return view('task.list', compact('list', 'status', 'sort'));
    }

    /**
     * Store task
     *
     * @param StoreTaskRequest $request
     * @return void
     */
    public function store(StoreTaskRequest $request)
    {
        $upload = $this->upload($request);

        if (!$upload && $request->image) {
            return response()->json(['message' => 'Error uploading file!'], 500);
        }

        $q = Task::create([
            'title' => $request->title ?? null,
            'contents' => $request->content ?? null,
            'status_id' => $request->status ?? null,
            'publish' => $request->publish ?? 0,
            'user_id' => $request->user() ? $request->user()->id : 0,
            'parent_id' => $request->parent ? $request->parent : 0,
            'image' =>  $request->image ? $request->image->getClientOriginalName() : null,
        ]);

        if(!$q){
            return response()->json(['message' => 'Error saving data!'], 500);
        }

        return redirect()->route('task');
    }

    /**
     * Create task
     *
     * @return void
     */
    public function create(Request $request)
    {
        $status = Status::orderBy('id', 'ASC')->get();

        $availableTask = $this->getUserCompleteTask($request);

        return view('task.addForm', compact('status', 'availableTask'));
    }

    /**
     * Edit Task
     *
     * @param [type] $id
     * @return void
     */
    public function edit(Request $request, $id)
    {
        $task = Task::with('status')->find($id);
        $status = Status::orderBy('id', 'ASC')->get();

        $availableTask = $this->getUserCompleteTask($request);

        return view('task.editForm',compact('task', 'status', 'availableTask'));
    }

    /**
     * Update task
     *
     * @param UpdateTaskRequest $request
     * @return void
     */
    public function update(UpdateTaskRequest $request)
    {
        $id = isset($request->id) ? intval($request->id) : 0;

        if($id === 0){
            return response()->json(['message' => 'Missing required parameters!'], 500);
        }

        $task = Task::findOrFail($id);

        if($task) {

            $task->title = $request->title ?? null;
            $task->contents = $request->content ?? null;
            $task->status_id = $request->status ?? null;
            $task->is_published = $request->publish ?? 0;
            $task->user_id = $request->user() ? $request->user()->id : 0;

            if($request->image){
                $task->image = $request->image->getClientOriginalName();
            }

            $upload = $this->upload($request);

            if (!$upload && $request->image) {
                return response()->json(['message' => 'Error uploading file!'], 500);
            }

            if ($task->isDirty('title')) {
                $request->validate([
                    'title' => 'required|max:100|unique:tasks',
                ]);
            }
            
            if (!$task->save()) {
                return response()->json(['message' => 'Error updating data!'], 500);
            }

            // update parent status
            if ($task->parent_id > 0) {
                $parentId = $task->parent_id;

                $doneSubTasks = Task::where('parent_id', $parentId)->where('status_id', 3)->count();
                $totalSubTasks = Task::where('parent_id', $parentId)->count();

                $statusID = $doneSubTasks === $totalSubTasks ? 3 : 2;

                // update parent status to done
                Task::where('id', $parentId)->update(['status_id' => $statusID]);
            }
        }
        
        return redirect()->route('task');
    }

    /** 
     * Upload image to local storage
     *
     * @param Request $request
     * @return boolean
     */
    private function upload (Request $request): bool
    {
        if ($request->file() && $request->image) {
            $file_name = $request->image->getClientOriginalName();
            
            return $request->file('image')->storeAs('uploads', $file_name, 'public');
        }

        return 0;
    }

    public function getUserCompleteTask(Request $request): array
    {
        $userId = $request->user()->id ?? 0;

        $q = Task::where('user_id', $userId)->orderBy('id', 'DESC')->get();

        if(!$q){
            return [];
        }

        return $q ? $q->toArray() : [];
    }
}

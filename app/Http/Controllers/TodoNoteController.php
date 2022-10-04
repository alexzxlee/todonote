<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TodoNote;
use Carbon\Carbon;
use Exception;

class TodoNoteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'arbitraryIndex']);
    }

    /**
     * Display a listing of the todo notes for the logged in user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Auth::user()->todoNotes;
    }

    /**
     * Display a listing of the todo notes for an arbitrary user.
     *
     * @return \Illuminate\Http\Response
     */
    public function arbitraryIndex($userId)
    {
        $todoNotes = TodoNote::where('user_id', '=', $userId)->get();

        return response()->json(['status'=>'success', 'todonotes' => $todoNotes], 200);
    }

    /**
     * Store a newly created todo note in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $this->validate($request, ['content' => 'required']);

            Auth::user()->todoNotes()->create(['content' => $request->content]);

            return response()->json(['status'=>'success', 'message' => 'The todo note has been saved.'], 200);

        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
        }

        return response()->json(['status'=>'error', 'message' => 'Failed to store: ' . $errorMessage], 406);
    }

    /**
     * Display the specified todo note.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $todoNote = TodoNote::find($id);

            if (Auth::user()->id !== $todoNote->user_id) {
                return response()->json(['status' => 'error', 'message' => 'unauthorized'], 401);
            }

            if(!$todoNote) {
                return response()->json(['status' => 'error', 'message' => 'That todo note is not found.'], 404);
            }

            return response()->json(['status'=>'success', 'todonote' => $todoNote], 200);

        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
        }

        return response()->json(['status'=>'error', 'message' => 'Failed to show: ' . $errorMessage], 406);
    }

    /**
     * Update the specified todo note in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $todoNote = TodoNote::find($id);

            if (Auth::user()->id !== $todoNote->user_id) {
                return response()->json(['status' => 'error', 'message' => 'unauthorized'], 401);
            }

            $todoNote->update($request->all());

            return response()->json(['status'=>'success', 'todonote' => $todoNote], 200);

        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
        }

        return response()->json(['status'=>'error', 'message' => 'Failed to update: ' . $errorMessage], 406);
    }

    /**
     * Remove the specified todo note from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $todoNote = TodoNote::find($id);

            if (Auth::user()->id !== $todoNote->user_id) {
                return response()->json(['status' => 'error', 'message' => 'unauthorized'], 401);
            }

            if (TodoNote::destroy($id)) {
                return response()->json(['status'=>'success','message' => 'That todo note is deleted successfully.'], 200);
            }
        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
        }

        return response()->json(['status'=>'error', 'message' => 'Failed to delete: ' . $errorMessage], 406);
    }

    /**
     * Mark a todo note as complete (set the completion time to NOW()).
     * Only on todo notes that the logged in user owns.
     *
     * @param int $id
     * @return void
     */
    public function complete($id)
    {
        try {
            $todoNote = TodoNote::find($id);

            if (Auth::user()->id !== $todoNote->user_id) {
                return response()->json(['status' => 'error', 'message' => 'unauthorized'], 401);
            }

            if ($todoNote->completion_time == null) {
                $todoNote->update(['completion_time' => Carbon::now()]);
                return response()->json(['status' => 'success', 'message' => 'Mark the todo item as done.'], 200);
            }
            
            return response()->json(['status'=>'success', 'message' => 'Todo item was previously marked as done.'], 200);

        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
        }

        return response()->json(['status'=>'error', 'message' => 'Failed to mark as complete: ' . $errorMessage], 406);
    }

    /**
     * Mark a todo note as incomplete (set the completion time NULL).
     * Only on todo notes that the logged in user owns.
     *
     * @param int $id
     * @return void
     */
    public function incomplete($id)
    {
        try {
            $todoNote = TodoNote::find($id);

            if (Auth::user()->id !== $todoNote->user_id) {
                return response()->json(['status' => 'error', 'message' => 'unauthorized'], 401);
            }

            if ($todoNote->completion_time != null) {
                $todoNote->update(['completion_time' => null]);
                return response()->json(['status' => 'success', 'message' => 'Mark the todo item as  incomplete.'], 200);
            }
            
            return response()->json(['status'=>'success', 'message' => 'Todo item was originally incomplete.'], 200);

        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
        }

        return response()->json(['status'=>'error', 'message' => 'Failed to mark as incomplete: ' . $errorMessage], 406);
    }
}

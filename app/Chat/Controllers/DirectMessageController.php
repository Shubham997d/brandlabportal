<?php

namespace App\Chat\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Base\Http\Controllers\Controller;
use App\Chat\Models\DirectMessage;
use App\Chat\Repositories\DirectMessageRepository;
use App\Chat\Requests\StoreDirectMessageRequest;

class DirectMessageController extends Controller
{
    public function index(DirectMessageRepository $repository)
    {
        try {
            $messages = $repository->getAllDirectMessages(auth()->user()->id, request('receiver_id'));

            return response()->json([
                'status'   => 'success',
                'total'    => count($messages),
                'messages' => $messages,
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'status'   => 'error',
                'message'  => 'Something went wrong',
            ]);
        }
    }

    public function store(StoreDirectMessageRequest $request, DirectMessageRepository $repository)
    {
        try {
            $message = $repository->saveMessage($request->all());
            $message->load('user:id,avatar,username'); 

            return response()->json([
                'status'  => 'success',
                'message' => $message,
            ]);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function update(StoreDirectMessageRequest $request, DirectMessage $directMessage)
    {
        try {
            $directMessage->update(['body' => $request->get('body')]);
            $directMessage->load('user');

            return response()->json([
                'status'  => 'success',
                'message' => $directMessage,
            ]);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function delete(Request $request,DirectMessage $directMessage)
    {        
        $directMessage = $directMessage->where('id',$request->id)->first();
        $this->authorize('delete', $directMessage);
        $directMessage->delete();        
        return response()->json(
            [
                'status'  => 'success',
                'message' => localize('misc.The direct message has been deleted'),
            ]
        );
    }
}

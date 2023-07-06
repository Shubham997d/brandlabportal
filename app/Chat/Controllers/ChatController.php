<?php

namespace App\Chat\Controllers;

use App\Base\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Chat\Repositories\ChatRepository;
use App\Chat\Repositories\DirectMessageRepository;

class ChatController extends Controller
{
    public function index(StatusRepository $statusRepository)
    {
      
    }

    public function getCommonConversationData(Request $request, DirectMessageRepository $directMessageRepository)
    {
        try { 
            $directUsers = $directMessageRepository->getDirectUsersWithLatestMessages();             
            return response()->json(['status'=> 'success', 'directUsers'=> $directUsers]);

        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }
}

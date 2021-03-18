<?php

namespace App\Modules\Chat\Middleware;

use App\Modules\Chat\Models\Repositories\Contracts\ConversationInterface;
use App\Modules\Chat\Models\Repositories\ConversationRepository;
use Illuminate\Http\Request;
use Closure;

class ConversationMiddleware
{

    /**
     * @var ConversationRepository
     */
    protected $conversationRepo;

    /**
     * Constructor.
     *
     * @param ConversationInterface $conversationRepo
     * @author xuanhieupd
     */
    public function __construct(ConversationInterface $conversationRepo)
    {
        $this->conversationRepo = $conversationRepo;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $conversationId = $request->route('conversationId', -1);
        $conversationInfo = $this->conversationRepo->find($conversationId);
        if (!$conversationInfo) return response()->responseError('Không tìm thấy thông tin cuộc hội thoại');

        $request->merge(array('conversation' => $conversationInfo));
        return $next($request);
    }

}

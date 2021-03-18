<?php

namespace App\Modules\Chat\Middleware;

use App\Modules\Chat\Models\Repositories\Contracts\MessageInterface;
use App\Modules\Chat\Models\Repositories\MessageRepository;
use Illuminate\Http\Request;
use Closure;

class MessageMiddleware
{

    /**
     * @var MessageRepository
     */
    protected $messageRepo;

    /**
     * Constructor.
     *
     * @param MessageInterface $messageRepo
     * @author xuanhieupd
     */
    public function __construct(MessageInterface $messageRepo)
    {
        $this->messageRepo = $messageRepo;
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
        $messageId = $request->route('messageId', -1);
        $messageInfo = $this->messageRepo->find($messageId);
        if (!$messageInfo) return response()->responseError('Không tìm thấy thông tin tin nhắn');

        $request->merge(array('message' => $messageInfo));
        return $next($request);
    }

}

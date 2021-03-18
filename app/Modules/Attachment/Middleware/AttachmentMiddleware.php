<?php

namespace App\Modules\Attachment\Middleware;

use App\Modules\Attachment\Models\Repositories\Contracts\AttachmentInterface;
use App\Modules\Attachment\Models\Repositories\Eloquents\AttachmentRepository;
use Illuminate\Http\Request;
use Closure;

class AttachmentMiddleware
{

    /**
     * @var AttachmentRepository
     */
    private $attachmentRepo;

    /**
     * Constructor.
     *
     * @param AttachmentInterface $orderRepo
     * @author xuanhieupd
     */
    public function __construct(AttachmentInterface $attachmentRepo)
    {
        $this->attachmentRepo = $attachmentRepo;
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
        $attachmentId = $request->route('attachmentId', -1);

        $attachmentInfo = $this->attachmentRepo->getAttachmentById($attachmentId);
        if (!$attachmentInfo) {
            return response()->responseError('Không tìm thấy thông tin file');
        }

        $request->merge(array('attachment' => $attachmentInfo));
        return $next($request);
    }

}

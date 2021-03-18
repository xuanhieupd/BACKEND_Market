<?php

namespace App\Modules\Attachment\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Attachment\Models\Entities\Attachment;
use App\Modules\Attachment\Models\Repositories\Contracts\AttachmentInterface;
use App\Modules\Attachment\Models\Repositories\Eloquents\AttachmentRepository;
use App\Modules\Attachment\Utils\FileUtil;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class IndexController extends AbstractController
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
     * Hiển thị file
     *
     * @param Request $request
     * @return mixed
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $attachmentId = $request->route('attachmentId');
        $attachmentInfo = $this->attachmentRepo->getAttachmentById($attachmentId, array(), array(
            'withs' => array('attachmentData'),
        ));

        if (!$attachmentInfo) {
            return $this->responseError('File không tồn tại');
        }

        $tempHash = $request->get('hash');
        if (
            !blank($attachmentInfo->getAttribute('temp_hash')) &&
            $attachmentInfo->getAttribute('temp_hash') !== $tempHash &&
            !$attachmentInfo->canView()
        ) {
            return $this->noPermission();
        }

        $attachmentData = $attachmentInfo->attachmentData;
        if (!$attachmentData || !$attachmentData->isDataAvailable()) {
            return $this->responseError('File không thể xem tại thời điểm hiện tại');
        }

        return $this->attachmentResponse($attachmentInfo);
    }

    /**
     * Response
     *
     * @param Attachment $attachment
     * @return BinaryFileResponse
     * @author xuanhieupd
     */
    public function attachmentResponse(Attachment $attachment)
    {
        $attachmentData = $attachment->attachmentData;
        return response()->file(FileUtil::resolvePath($attachmentData->getAbstractedDataPath()));
    }

}

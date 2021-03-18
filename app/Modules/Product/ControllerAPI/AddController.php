<?php

namespace App\Modules\Product\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Attachment\Models\Services\PreparerService;
use App\Modules\Product\Models\Entities\Product;
use App\Modules\Product\Requests\AddRequest;

class AddController extends AbstractController
{

    /**
     * @var PreparerService
     */
    private $attachmentService;

    /**
     * Constructor.
     *
     * @param PreparerService $attachmentService
     * @author xuanhieupd
     */
    public function __construct(PreparerService $attachmentService)
    {
        $this->attachmentService = $attachmentService;
    }

    /**
     * Thêm mới sản phẩm
     *
     * @param AddRequest $request
     * @author xuanhieupd
     */
    public function actionIndex(AddRequest $request)
    {
        $productInfo = new Product(array());

        $this->attachmentService->associateAttachmentsWithContent($this->getAttachmentHash(), 'product', $productInfo->getId());
    }

}

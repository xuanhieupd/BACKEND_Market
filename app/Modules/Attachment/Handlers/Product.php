<?php

namespace App\Modules\Attachment\Handlers;

use App\Modules\Attachment\AbstractHandler;
use App\Modules\Attachment\Models\Entities\Attachment;
use App\Modules\Attachment\Models\Repositories\Contracts\AttachmentInterface;
use App\Modules\Product\Exceptions\ProductNotFoundException;
use App\Modules\Product\Models\Repositories\Contracts\ProductInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Product extends AbstractHandler
{

    /**
     * Có thể xem file hay không ?
     *
     * @param Attachment $attachment
     * @param Model $container
     * @param null $error
     * @return bool
     * @author xuanhieupd
     */
    public function canView(Attachment $attachment, Model $container, &$error = null)
    {
        return $container->canView();
    }

    /**
     * Có thể upload hay không ?
     *
     * @param array $context
     * @return bool
     * @author xuanhieupd
     */
    public function canManageAttachments(array $context)
    {
        $visitor = Auth::user();
        $productId = $this->getContainerIdFromContext($context);

        if (!$productId) {
            return true;
        }

        try {
            $productInfo = app(ProductInterface::class)->getStoreProductById($visitor->getStoreId(), $productId, array(), array(
                'fields' => array('product_id'),
            ));
        } catch (ProductNotFoundException $e) {
            return false;
        }

        return $productInfo->exists();
    }

    /**
     * Điều kiện upload
     *
     * @param array $context
     * @return mixed
     * @author xuanhieupd
     */
    public function getConstraints(array $context)
    {
        return app(AttachmentInterface::class)->getDefaultAttachmentConstraints();
    }

    public function onAttachmentDelete(Attachment $attachment, Model $container = null)
    {

    }

    public function getContainerIdFromContext(array $context)
    {
        return data_get($context, 'product_id');
    }

    public function getContainerLink(Model $container, array $extraParams = [])
    {
        // TODO: Implement getContainerLink() method.
    }

    public function getContext(Model $entity = null, array $extraContext = [])
    {
        return $extraContext;
    }

    public function getContainerEntity($id)
    {
        $visitor = Auth::user();
        return app(ProductInterface::class)->getStoreProductById($visitor->getStoreId(), $id);
    }
}

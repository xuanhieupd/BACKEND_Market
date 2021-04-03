<?php

namespace App\Modules\Cart\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Cart\Models\Repositories\Contracts\OrderCartInterface;
use App\Modules\Cart\Models\Repositories\Eloquents\OrderCartRepository;
use App\Modules\Cart\Requests\AddRequest;
use App\Modules\Product\Modules\Variant\Models\Repositories\Contracts\VariantInterface;
use App\Modules\Product\Modules\Variant\Models\Repositories\Eloquents\VariantRepository;
use Illuminate\Support\Facades\Auth;

class AddController extends AbstractController
{

    /**
     * @var VariantRepository
     */
    protected $variantRepo;

    /**
     * @var OrderCartRepository
     */
    protected $cartRepo;

    /**
     * Constructor
     *
     * @param VariantInterface $variantRepo
     * @author xuanhieupd
     */
    public function __construct(VariantInterface $variantRepo, OrderCartInterface $cartRepo)
    {
        $this->variantRepo = $variantRepo;
        $this->cartRepo = $cartRepo;
    }

    /**
     * Thêm sản phẩm vào giỏ
     *
     * @param AddRequest $request
     * @return mixed
     * @throws \Exception
     * @author xuanhieupd
     */
    public function actionIndex(AddRequest $request)
    {
        $visitor = Auth::user();
        $variants = $this->variantRepo->getVariantsByIds($request->getVariantIds());
        if ($variants->isEmpty()) return $this->responseError('Dữ liệu không hợp lệ');

        $insertResults = array();
        $productInfo = $variants[0]->variantProduct;

        foreach ($variants as $variantInfo) {
            $insertResults[] = array(
                'user_id' => $visitor->getId(),
                'store_id' => $variantInfo->getAttribute('store_id'),
                'product_id' => $variantInfo->getAttribute('product_id'),
                'variant_id' => $variantInfo->getAttribute('variant_id'),
                'price' => $productInfo->getAttribute('whole_price'),
                'quantity' => $request->getQuantity($variantInfo->getId()),
                'created_at' => now(), 'updated_at' => now(),
                'relation_store_id' => 0
            );
        }

        $this->cartRepo->getCarts(auth()->id())->whereIn('variant_id', $request->getVariantIds())->delete();
        $this->cartRepo->insert($insertResults);

        return $this->responseMessage('Thành công');
    }

}

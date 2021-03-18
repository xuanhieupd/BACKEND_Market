<?php

namespace Database\Seeders;

use App\Modules\Product\Models\Entities\Product;
use App\Modules\Product\Models\Repositories\Contracts\ProductInterface;
use App\Modules\Product\Models\Repositories\Eloquents\ProductRepository;
use Illuminate\Database\Seeder;
use Illuminate\Pagination\Paginator;

class ProductAttachmentSeeder extends Seeder
{
    /**
     * @var ProductRepository
     */
    protected $productRepo;

    /**
     * Constructor.
     *
     * @param ProductInterface $productRepo
     * @author xuanhieupd
     */
    public function __construct(ProductInterface $productRepo)
    {
        $this->productRepo = $productRepo;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $page = 1;
        while (true) {
            $products = $this->recursion($page);
            if ($products->isEmpty()) break;
            $page++;
        }
    }

    protected function recursion($currentPage)
    {
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

        $products = $this->productRepo->getProducts()
            ->select(array('product_id', 'attachment_id'))
            ->with(array('productAttachments'))
            ->simplePaginate(100);

        foreach ($products as $productInfo) {
            $attachments = $productInfo->productAttachments;
            $attachmentInfo = $attachments ? $attachments->first() : null;
            if (!$attachmentInfo) continue;

            $productInfo->setAttribute('attachment_id', $attachmentInfo->getId());
            $productInfo->save();
        }

        return $products;
    }
}

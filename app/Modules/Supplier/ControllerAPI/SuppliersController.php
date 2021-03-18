<?php
/**
 * Suppliers Controller
 *
 * @author xuanhieupd
 * @package Supplier
 * @copyright 14.11.2020, HNW
 */

namespace App\Modules\Supplier\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Supplier\Models\Repositories\Contracts\SupplierInterface;
use App\Modules\Supplier\Models\Repositories\Eloquents\SupplierRepository;
use App\Modules\Supplier\Resources\SupplierResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuppliersController extends AbstractController
{
    /**
     * @var SupplierRepository
     */
    private $supplierRepo;

    /**
     * Constructor.
     *
     * @param SupplierInterface $supplierRepo
     * @return void
     * @author xuanhieupd
     */
    public function __construct(SupplierInterface $supplierRepo)
    {
        $this->supplierRepo = $supplierRepo;
    }

    /**
     * Hiển thị danh sách nhà cung cấp
     *
     * @param Request $request
     * @return SupplierResource
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $visitor = Auth::user();
        $suppliers = $this->supplierRepo->getStoreSuppliers($visitor->getStoreId());

        return SupplierResource::collection($suppliers);
    }

}



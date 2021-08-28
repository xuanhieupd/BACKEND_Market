<?php

namespace App\Modules\ShortUrl\ControllerPublic;

use App\Base\AbstractController;
use App\Modules\Feed\Models\Repositories\Contracts\FeedInterface;
use App\Modules\Product\Models\Repositories\Contracts\ProductInterface;
use App\Modules\ShortUrl\Exceptions\SpecificException;
use App\Modules\ShortUrl\Models\Services\ShortUrlService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class FeedController extends AbstractController
{

    /**
     * @var FeedInterface
     */
    protected $feedRepo;

    /**
     * Constructor.
     *
     * @param FeedInterface $feedRepo
     * @author xuanhieupd
     */
    public function __construct(FeedInterface $feedRepo)
    {
        $this->feedRepo = $feedRepo;
    }

    /**
     * @param Request $request
     * @return mixed
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        try {
            $data = ShortUrlService::specific($request->route('modelId'));
            $feedInfo = $this->feedRepo->getFeedById($data['modelId'])->first();
            if (!$feedInfo) return abort(404);

            $fullUrl = ShortUrlService::toUrl($data['appId'], strtr('feed/:feedId', array(':feedId' => $feedInfo->getId())));
            if (blank($fullUrl)) return abort(404);

            return Redirect::to($fullUrl);
        } catch (SpecificException $e) {
            return abort(404);
        }
    }

}

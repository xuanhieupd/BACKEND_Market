<?php

namespace App\Modules\ShortUrl\ControllerPublic;

use App\Base\AbstractController;
use App\Modules\ShortUrl\Models\Repositories\Contracts\ShortUrlInterface;
use Illuminate\Http\Request;

class RedirectController extends AbstractController
{

    /**
     * @var ShortUrlInterface
     */
    protected $shortUrlRepo;

    /**
     * Constructor.
     *
     * @param ShortUrlInterface $shortUrlRepo
     * @author xuanhieupd
     */
    public function __construct(ShortUrlInterface $shortUrlRepo)
    {
        $this->shortUrlRepo = $shortUrlRepo;
    }

    /**
     * Redirect tới LongURL
     *
     * @param Request $request
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $codeValue = $request->route('code');
        if (blank($codeValue)) return abort(404);

        $shortUrl = $this->shortUrlRepo->getShortUrlByCode($codeValue);
        if (!$shortUrl) return abort(404);

        return redirect($shortUrl->getLongUrl());
    }

}

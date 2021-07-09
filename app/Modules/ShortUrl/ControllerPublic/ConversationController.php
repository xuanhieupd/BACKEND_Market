<?php

namespace App\Modules\ShortUrl\ControllerPublic;

use App\Base\AbstractController;
use App\Modules\Chat\Models\Repositories\Contracts\ConversationInterface;
use App\Modules\ShortUrl\Exceptions\SpecificException;
use App\Modules\ShortUrl\Models\Services\ShortUrlService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ConversationController extends AbstractController
{

    /**
     * @var ConversationInterface
     */
    protected $conversationRepo;

    /**
     * Constructor.
     *
     * @param ConversationInterface $conversationRepo
     * @author xuanhieupd
     */
    public function __construct(ConversationInterface $conversationRepo)
    {
        $this->conversationRepo = $conversationRepo;
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
            $conversationInfo = $this->conversationRepo->makeModel()->where('conversation_id', $data['modelId'])->first();
            if (!$conversationInfo) return abort(404);

            $fullUrl = ShortUrlService::toUrl($data['appId'], strtr('conversation/:conversationId', array(':conversationId' => $conversationInfo->getId())));
            if (blank($fullUrl)) return abort(404);

            return Redirect::to($fullUrl);
        } catch (SpecificException $e) {
            return abort(404);
        }
    }

}

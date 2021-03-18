<?php

namespace App\Modules\Order\Modules\Note\ControllerAPI;

use App\Base\AbstractController;
use App\Modules\Order\Resources\NoteResource;
use Illuminate\Http\Request;

class NotesController extends AbstractController
{

    /**
     * Danh sách ghi chú
     *
     * @param Request $request
     * @return NoteResource
     * @author xuanhieupd
     */
    public function actionIndex(Request $request)
    {
        $orderInfo = $request->input('order');

        $orderInfo->load(array(
            'orderNotes',
            'orderNotes.noteUser',
        ));

        return NoteResource::collection($orderInfo->orderNotes);
    }

}

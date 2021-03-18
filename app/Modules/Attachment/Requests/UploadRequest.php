<?php

namespace App\Modules\Attachment\Requests;

use App\Base\AbstractRequest;
use App\Modules\Attachment\Models\Entities\Attachment;
use Illuminate\Http\UploadedFile;

class UploadRequest extends AbstractRequest
{

    /**
     * Rules
     *
     * @return array
     * @author xuanhieupd
     */
    public function rules()
    {
        return array(
            'hash' => 'required|string',
            'handler_id' => 'required|string|in:' . implode(',', Attachment::getHandlerIds()),
            'data' => 'required',
        );
    }

    /**
     * @return UploadedFile
     * @author xuanhieupd
     */
    public function getFileUpload()
    {
        return $this->file('data');
    }

    /**
     * Handler Id
     *
     * @return string
     * @author xuanhieupd
     */
    public function getHandlerId()
    {
        return $this->get('handler_id');
    }

    /**
     * Hash uploading
     *
     * @return string
     * @author xuanhieupd
     */
    public function getHash()
    {
        return $this->get('hash');
    }

}

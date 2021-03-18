<?php

namespace App\Libraries\Chat\Eventing;

use App\Libraries\Chat\Models\Participation;
use App\Modules\Chat\Helpers\ChatHelper;
use App\Modules\Chat\Resources\Message\MessageResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use App\Libraries\Chat\Models\Message;

class MessageWasSent extends Event implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $message;

    public $participation;

    /**
     * Constructor.
     *
     * @param Message $message
     * @author xuanhieupd
     */
    public function __construct(Participation $participation, Message $message)
    {
        $this->participation = $participation;
        $this->message = $message;
    }

    /**
     * @return MessageResource
     */
    public function broadcastWith()
    {
        $messageInfo = ChatHelper::loadMessageResource($this->message);
        $messageResource = (new MessageResource($messageInfo))->toArray(new Request());
        $messageResource['conversation_id'] = $messageInfo->getAttribute('conversation_id');

        return $messageResource;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     * @author xuanhieupd
     */
    public function broadcastOn()
    {
        $classMessageable = $this->participation->getAttribute('messageable_type');
        $classInstance = new $classMessageable;

        $channelName = strtr('chat.conversation.:participation', array(
            ':participation' => implode('.', array(
                $classInstance->getType(),
                $this->participation->getAttribute('messageable_id')
            ))
        ));

        return new PrivateChannel(strtolower($channelName));
    }

    /**
     * @return string
     */
    public function broadcastAs()
    {
        return 'message.sent';
    }

}

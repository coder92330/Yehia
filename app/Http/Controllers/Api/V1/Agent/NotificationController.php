<?php

namespace App\Http\Controllers\Api\V1\Agent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Agent\DeviceKey\DeviceKeyRequest;
use App\Http\Resources\Api\V1\Agent\NewsNotificationResource;
use App\Http\Resources\Api\V1\Agent\NotificationResource;
use App\Http\Resources\Api\V1\ErrorResource;
use App\Http\Resources\Api\V1\SuccessResource;
use App\Models\AgentNewsNotification;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth('agent_api')->user()->notifications()->paginate(config('app.pagination'));
        return count($notifications) > 0
            ? NotificationResource::collection($notifications)->additional(['un_readed_notifications' => auth('agent_api')->user()->unreadNotifications()->count()])
            : NotificationResource::collection($notifications)->additional(['message' => __('messages.no_notifications'), 'un_readed_notifications' => auth('agent_api')->user()->unreadNotifications()->count()]);
    }

    public function unreadNotifications()
    {
        $unreadNotifications = auth('agent_api')->user()->unreadNotifications()->paginate(config('app.pagination'));
        return count($unreadNotifications) > 0
            ? NotificationResource::collection($unreadNotifications)->additional(['un_readed_notifications' => auth('agent_api')->user()->unreadNotifications()->count()])
            : NotificationResource::collection($unreadNotifications)->additional(['message' => __('messages.no_unread_notifications'), 'un_readed_notifications' => auth('agent_api')->user()->unreadNotifications()->count()]);
    }

    public function unreadNotificationsCount(): SuccessResource|ErrorResource
    {
        try {
            return SuccessResource::make(auth('agent_api')->user()->unreadNotifications()->count(), 200, 'un_readed_notifications');
        } catch (\Exception $e) {
            Log::channel('agent')->error("Error in NotificationController@unreadNotificationsCount: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return ErrorResource::make(__('messages.something_went_wrong'));
        }
    }

    public function show($id): ErrorResource|NotificationResource
    {
        $notification = auth('agent_api')->user()->notifications()->find($id);
        return $notification
            ? NotificationResource::make($notification)
            : ErrorResource::make(__('messages.notification_not_found'), 404);
    }

    public function update($id): SuccessResource|ErrorResource
    {
        try {
            $notification = auth('agent_api')->user()->notifications()->find($id);
            if ($notification) {
                $notification->markAsRead();
                return SuccessResource::make(['message' => __('messages.notification_marked_as_read'), 'un_readed_notifications' => auth('agent_api')->user()->unreadNotifications()->count()]);
            }
            return ErrorResource::make(__('messages.notification_not_found'));
        } catch (\Exception $e) {
            Log::channel('agent')->error("Error in NotificationController@update: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return ErrorResource::make(__('messages.something_went_wrong'));
        }
    }

    public function destroy($id): SuccessResource|ErrorResource
    {
        try {
            $notification = auth('agent_api')->user()->notifications()->find($id);
            if ($notification) {
                $notification->delete();
                return SuccessResource::make(__('messages.notification_deleted'));
            }
            return ErrorResource::make(__('messages.notification_not_found'));
        } catch (\Exception $e) {
            Log::channel('agent')->error("Error in NotificationController@destroy: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return ErrorResource::make(__('messages.something_went_wrong'));
        }
    }

    public function markAllAsRead(): SuccessResource|ErrorResource
    {
        try {
            if (auth('agent_api')->user()->unreadNotifications->count() > 0) {
                auth('agent_api')->user()->unreadNotifications->markAsRead();
                return SuccessResource::make(['message' => __('messages.all_notifications_marked_as_read'), 'un_readed_notifications' => auth('agent_api')->user()->unreadNotifications()->count()]);
            }
            return ErrorResource::make(__('messages.no_unread_notifications'));
        } catch (\Exception $e) {
            Log::channel('agent')->error("Error in NotificationController@markAllAsRead: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return ErrorResource::make(__('messages.something_went_wrong'));
        }
    }

    public function deleteAll(): SuccessResource|ErrorResource
    {
        try {
            if (auth('agent_api')->user()->notifications->count() > 0) {
                auth('agent_api')->user()->notifications()->delete();
                return SuccessResource::make(__('messages.all_notifications_deleted'));
            }
            return ErrorResource::make(__('messages.no_notifications'));
        } catch (\Exception $e) {
            Log::channel('agent')->error("Error in NotificationController@destroyAll: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return ErrorResource::make(__('messages.something_went_wrong'));
        }
    }

    public function deviceKey(DeviceKeyRequest $request): SuccessResource|ErrorResource
    {
        try {
            if (auth('agent_api')->user()->device_key !== $request->device_key) {
                auth('agent_api')->user()->update(['device_key' => $request->device_key]);
                return SuccessResource::make(__('messages.device_token_updated'));
            }
            return SuccessResource::make(__('messages.device_token_already_updated'));
        } catch (\Exception $e) {
            Log::channel('agent')->error("Error in NotificationController@deviceToken: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return ErrorResource::make(__('messages.something_went_wrong'));
        }
    }

    public function newsNotifications($id): NewsNotificationResource|ErrorResource
    {
        return ($newsNotification = AgentNewsNotification::find($id))
            ? NewsNotificationResource::make($newsNotification)
            : ErrorResource::make(__('messages.notification_not_found'), 404);
    }
}

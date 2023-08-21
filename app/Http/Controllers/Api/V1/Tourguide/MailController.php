<?php

namespace App\Http\Controllers\Api\V1\Tourguide;

use App\Mail\SendMail;
use App\Models\Mail as MailModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{DB, Log, Mail};
use App\Http\Requests\Api\V1\Agent\Mail\StoreMailRequest;
use App\Http\Resources\Api\V1\{Agent\MailResource, ErrorResource, SuccessResource};

class MailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection | ErrorResource
     */
    public function index()
    {
        $mails = auth('agent_api')->user()->mails()->paginate(config('app.pagination'));
        return count($mails) > 0
            ? MailResource::collection($mails)
            : ErrorResource::make(__('messages.no_data', ['attribute' => __('attributes.mails')]), 404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreMailRequest $request
     * @return SuccessResource | ErrorResource
     */
    public function store(StoreMailRequest $request)
    {
        try {
            DB::beginTransaction();
            $mail = auth('agent_api')->user()->mails()->create($request->safe()->all());
            if($request->has('attachments')) $media = $mail->addMediaFromRequest('attachments')->toMediaCollection('mails');
            $mailSent = Mail::to($request->to)->send(new SendMail($request->safe()->merge(['id' => $media->id])->all()));
            if ($mailSent) {
                $mail->update(['is_mail_sent' => true]);
                DB::commit();
                return SuccessResource::make(__('messages.mail_sent'));
            }
            return ErrorResource::make(__('messages.error_occurred'), 500);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('agent')->error("Error in MailController@store: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return ErrorResource::make(__('messages.error_occurred'), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return MailResource | ErrorResource
     */
    public function show($id)
    {
        return ($mail = auth('agent_api')->user()->mails()->find($id))
            ? MailResource::make($mail)
            : ErrorResource::make(__('messages.no_data', ['attribute' => __('attributes.mail')]), 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return SuccessResource | ErrorResource
     */
    public function destroy($id)
    {
        try {
            if (auth('agent_api')->user()->mails()->whereId($id)->exists()) {
                DB::beginTransaction();
                MailModel::find($id)->clearMediaCollection('mails');
                auth('agent_api')->user()->mails()->whereId($id)->delete();
                DB::commit();
                return SuccessResource::make(__('messages.success.deleted', ['attribute' => __('attributes.mail')]));
            }
            return ErrorResource::make(__('messages.no_data', ['attribute' => __('attributes.mail')]), 404);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('agent')->error("Error in MailController@destroy: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return ErrorResource::make(__('messages.error_occurred'), 500);
        }
    }
}
